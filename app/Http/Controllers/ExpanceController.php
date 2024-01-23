<?php

namespace App\Http\Controllers;

use App\Models\Expanse;
use App\Models\Method;
use App\Models\Purpose;
use App\Models\Transaction;
use App\Models\TransactionLine;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;


class ExpanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('expanse.index')){
            abort(401);
        }

        return inertia('Modules/Expanse/Index', [
            $search = Request::input('search'),
            'expanses' => Expanse::query()->with(['purpse', 'user','method'])
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('subject', 'like', "%{$search}%")
                            ->orWherehas('purpse', function ($query)use($search){
                                $query->where('name', 'like', "%{$search}%");
                            })->orWherehas('method', function ($query)use($search){
                                $query->where('name',    'like', "%{$search}%");
                            }) ->orWherehas('user', function ($query)use($search){
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                            });
                })->latest()
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($expance) => [
                    'id' => $expance->id,
                    'user' => $expance->user,
                    'purpse' => $expance->purpse,
                    'method' => $expance->method,
                    'amount' => $expance->amount,
                    'subject' => $expance->subject,
                    'date' => $expance->date->format('d M Y'),
                    'created_at' => $expance->created_at->format('d M Y'),
                    'show_url' => URL::route('expense.show', $expance->id),
                ]),
            'purposes' => Purpose::all(),
            'methods' => Method::all(),
            'filters' => Request::only(['search','perPage']),
            "main_url" => Url::route('expense.index'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('expanse.create')){
            abort(401);
        }

        Request::validate([
            'purpose_id' => 'required|integer',
//            'subject' => 'required',
            'amount' => 'required',
            'method_id' => 'required',
            'expanse_date' => 'required',
            'document' => 'max:20000'
        ]);

        $filePath = "";
        if (Request::hasFile('document')) {
            $filePath = Request::file('document')->store('expanses', 'public');
        }


        $purpose = Purpose::findOrFail(Request::input('purpose_id'));

        $expanse = Expanse::create([
            'u_id' => date('Yd', strtotime(now())),
            'purpose_id' => Request::input('purpose_id'),
            'subject' => Request::input('subject'),
            'amount' => Request::input('amount'),
            'method_id' => Request::input('method_id'),
            'user_id' => Auth::id(),
            'date' => Request::input('expanse_date'),
            'details' => Request::input('details'),
            'document' => $filePath
        ]);

        Transaction::create([
            'transaction_id' =>  now()->format('Ymd'),
            'transactionable_id' => $expanse->id,
            'transactionable_type' => "App\\Models\\Expanse",
            'received_by' => Auth::id(),
            "transaction_type" => "Debited",
            "amount" => Request::input('amount'),
            "pay" => Request::input('amount'),
            "due" => 0,
            "payment_date" => Request::input('expanse_date'),
            "method_id" => Request::input('method_id'),
            "purpose" => $purpose?->name ?? NULL,
            "note" => Request::input('details'),
        ]);
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\expanse  $expance
     * @return string
     */
    public function show($id)
    {
        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('expanse.show')){
            abort(401);
        }

        if (Request::input('data')){
            return Expanse::with(['purpse', 'user','method'])->findOrFail($id);
        }
        return "view";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\expanse  $expance
     * @return \Illuminate\Http\Response
     */
    public function edit(expanse $expance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\expanse  $expance
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('expanse.edit')){
            abort(401);
        }

        $expance = Expanse::findOrFail($id);
        Request::validate([
            'purpose_id' => 'required|integer',
//            'subject' => 'required',
            'amount' => 'required',
            'method_id' => 'required',
            'expanse_date' => 'required',
            'document' => 'nullable|max:20000'
        ]);
        $filePath = "";
        if (Request::hasFile('document')) {
            $file = Storage::disk('public')->get($expance->document);
            if ($file != null){
                Storage::disk('public')->delete($expance->document);
            }
            $filePath = Request::file('document')->store('expanses', 'public');
        }

        $expance->update([
            'u_id' => date('Yd', strtotime(now())),
            'purpose_id' => Request::input('purpose_id'),
            'subject' => Request::input('subject'),
            'amount' => Request::input('amount'),
            'method_id' => Request::input('method_id'),
            'user_id' => Auth::id(),
            'date' => Request::input('expanse_date'),
            'details' => Request::input('details'),
            'document' => $filePath
        ]);

        $transaction = Transaction::where('transactionable_type', "App\\Models\\Expanse")->where('transactionable_id', $expance->id)->first();
        $transaction->update([
            'transaction_id' =>  now()->format('Ymd'),
            'transactionable_id' => $expance->id,
            'transactionable_type' => "App\\Models\\Expanse",
            'received_by' => Auth::id(),
            "transaction_type" => "Debited",
            "amount" => Request::input('amount'),
            "pay" => Request::input('amount'),
            "due" => 0,
            "payment_date" => Request::input('expanse_date'),
            "method_id" => Request::input('method_id'),
            "note" => Request::input('details'),
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\expanse  $expance
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth()->user()->hasRole('administrator')  || !auth()->user()->can('expanse.delete')){
            abort(401);
        }

        $expance = Expanse::findOrFail($id);
        $transaction = Transaction::where('transactionable_type', "App\\Models\\Expanse")->where('transactionable_id', $expance->id)->first();
        if($transaction){
            $transaction->delete();
        }
        $expance->delete();
        return back();
    }

    public function resizeImagePost(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('image');
        $input['imagename'] = time().'.'.$image->getClientOriginalExtension();

        $destinationPath = public_path('/thumbnail');
        $img = Image::make($image->getRealPath());
        $img->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['imagename']);

        $destinationPath = public_path('/images');
        $image->move($destinationPath, $input['imagename']);

        $this->postImage->add($input);

        return back()
            ->with('success','Image Upload successful')
            ->with('imageName',$input['imagename']);
    }
}
