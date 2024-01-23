<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Dotenv\Dotenv;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class BusinessSettingController extends Controller
{

    public function get_setting($key, $default=null){
        if (!auth()->user()->can('settings.show')){
            abort(404);
        }
        $setting = BusinessSetting::where('type', $key)->first();
        return $setting == null ? $default : $setting->value;
    }

    public function getAllPolicy(){
        if (!auth()->user()->can('settings.show') || auth()->user()->hasRole('administrator')){
            abort(401);
        }



        $data = [
            'trams_and_condition' => $this->get_setting('trams_and_condition'),
            'payment_policy' => $this->get_setting('payment_policy'),
            'payment_methods' => $this->get_setting('payment_methods'),
        ];
        return $data;
    }

    public function overWriteEnv($key, $value)
    {
        if (!auth()->user()->can('settings.show') || auth()->user()->hasRole('administrator')){
            abort(401);
        }

        if (!auth()->user()->can('settings.show')){
            abort(404);
        }
        $envFile = base_path('.env');
        if (File::exists($envFile)) {
            // Read the contents of the .env file
            $contents = File::get($envFile);

            // Replace the existing value with the new value
            $updatedContents = Str::replaceFirst(
                "{$key}=" . env($key),
                "{$key}={$value}",
                $contents
            );

            // Write the updated contents back to the .env file
            File::put($envFile, $updatedContents);

            return "Environment variable updated successfully!";
        }
    }

    public function index()
    {
        if (!auth()->user()->can('settings.show') || auth()->user()->hasRole('administrator')){
            abort(401);
        }
        return inertia('Settings/Setting', [
            'main_url' => URL::route('businessIndex'),
            'updateSmtp' => URL::route('updateSmtp'),
            'bSettings' =>[
                'trams_and_condition' => $this->get_setting('trams_and_condition'),
                'payment_policy' => $this->get_setting('payment_policy'),
                'payment_methods' => $this->get_setting('payment_methods'),

                'quotation_template' => $this->get_setting('quotation_template'),
                'invoice_template' => $this->get_setting('invoice_template'),

                'mailDriver' => $this->get_setting('mailDriver'),
                'mailHost' => $this->get_setting('mailHost'),
                'mailPort' => $this->get_setting('mailPort'),
                'username' => $this->get_setting('username'),
                'password' => $this->get_setting('password'),
                'fromEmail' => $this->get_setting('fromEmail'),
                'fromName' => $this->get_setting('fromName'),
                'encryption' => $this->get_setting('encryption'),
            ]
        ]);
    }

    public function updateSettings(){
        if (!auth()->user()->can('settings.show') || auth()->user()->hasRole('administrator')){
            abort(401);
        }
        foreach (Request::all() as $type => $value) {
            $business_settings = BusinessSetting::where('type', $type)->first();
            if($business_settings != null) {
                if ($value != null){
                    if(gettype($value) == 'array'){
                        $business_settings->value = json_encode($value);
                    }
                    else {
                        $business_settings->value = $value;
                    }
                    $business_settings->save();
                }
            } else{
                if ($value != null){
                    $business_settings = new BusinessSetting;
                    $business_settings->type = $type;
                    if(gettype($value) == 'array'){
                        $business_settings->value = json_encode($value);
                    }
                    else {
                        $business_settings->value = $value;
                    }
                    $business_settings->save();
                }
            }
        }
        return back();
    }


    public function updateSmtp(){
        if (!auth()->user()->can('settings.show') || auth()->user()->hasRole('administrator')){
            abort(401);
        }
        foreach (Request::all() as $type => $value) {
            $business_settings = BusinessSetting::where('type', $type)->first();
            if ($type == 'mailDriver' && $value != null){
                $this->overWriteEnv("MAIL_MAILER", $value);
            }
            if ($type == 'mailHost' && $value != null){
                $this->overWriteEnv("MAIL_HOST", $value);
            }
            if ($type == 'mailPort' && $value != null){
                $this->overWriteEnv("MAIL_PORT", $value);
            }
            if ($type == 'username' && $value != null){
                $this->overWriteEnv("MAIL_USERNAME", $value);
            }
            if ($type == 'password' && $value != null){
                $this->overWriteEnv("MAIL_PASSWORD", $value);
            }
            if ($type == 'fromEmail' && $value != null){
                $this->overWriteEnv("MAIL_FROM_ADDRESS", $value);
            }
            if ($type == 'fromName' && $value != null){
                $this->overWriteEnv("MAIL_FROM_NAME", $value);
            }
            if ($type == 'encryption' && $value != null){
                $this->overWriteEnv("MAIL_ENCRYPTION", $value);
            }
            if($business_settings != null) {
                if ($value != null){
                    if(gettype($value) == 'array'){
                        $business_settings->value = json_encode($value);
                    }
                    else {
                        $business_settings->value = $value;
                    }
                    $business_settings->save();
                }
            } else{
                if ($value != null){
                    $business_settings = new BusinessSetting;
                    $business_settings->type = $type;
                    if(gettype($value) == 'array'){
                        $business_settings->value = json_encode($value);
                    }
                    else {
                        $business_settings->value = $value;
                    }
                    $business_settings->save();
                }
            }
        }
        return back();
    }

}
