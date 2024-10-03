import moment from "moment";

export function useDate(date = null){
    let formatted = (date)=>{
        return moment().format('ll');
    }


    const ranges = {};
    ranges["Today"] = [moment().format('YYYY-MM-DD'), moment().format('YYYY-MM-DD')];
    ranges["Yesterday"] = [moment().subtract(1, 'days').format('YYYY-MM-DD'), moment().subtract(1, 'days').format('YYYY-MM-DD')];
    ranges["Last 7 Days"] = [moment().subtract(6, 'days').format('YYYY-MM-DD'), moment().format('YYYY-MM-DD')];
    ranges["Last 30 Days"] = [moment().subtract(29, 'days').format('YYYY-MM-DD'), moment().format('YYYY-MM-DD')];
    ranges["This Month"] = [moment().startOf('month').format('YYYY-MM-DD'), moment().endOf('month').format('YYYY-MM-DD')];
    ranges["Last Month"] = [moment().subtract(1, 'month').startOf('month').format('YYYY-MM-DD'),moment().subtract(1, 'month').endOf('month').format('YYYY-MM-DD')];
    ranges["This Month Last Year"] = [moment().subtract(1, 'year').startOf('month').format('YYYY-MM-DD'),moment().subtract(1, 'year').endOf('month').format('YYYY-MM-DD'),];
    ranges["This Year"] = [moment().startOf('year').format('YYYY-MM-DD'), moment().endOf('year').format('YYYY-MM-DD')];
    ranges["Last Year"] = [moment().startOf('year').subtract(1, 'year').format('YYYY-MM-DD'),moment().endOf('year').subtract(1, 'year').format('YYYY-MM-DD')];



    return { formatted, ranges }
}
