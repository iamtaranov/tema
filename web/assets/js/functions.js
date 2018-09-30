
/**
 * @param selector
 * @param callback
 */
function setClickListeners(selector, callback)
{
    [].forEach.call(selector, function(item)
    {
        item.addEventListener("click", function() { callback(item) });
    });
}

/**
 * By Kevin van Zonneveld (http://kevin.vanzonneveld.net)
 * @param input
 * @returns {any[]}
 */
function array_values(input)
{
    var tmp_arr = new Array(), cnt = 0;

    for (key in input){
        tmp_arr[cnt] = input[key];
        cnt++;
    }

    return tmp_arr;
}

/**
 * Kevin van Zonneveld (http://kevin.vanzonneveld.net)
 * @param input
 * @param search_value
 * @param strict
 * @returns {any[]}
 */
function array_keys(input, search_value, strict)
{
    var tmp_arr = new Array(), strict = !!strict, include = true, cnt = 0;

    for ( key in input ){
        include = true;
        if ( search_value != undefined ) {
            if( strict && input[key] !== search_value ){
                include = false;
            } else if( input[key] != search_value ){
                include = false;
            }
        }

        if( include ) {
            tmp_arr[cnt] = key;
            cnt++;
        }
    }

    return tmp_arr;
}

/**
 * @param obj
 * @param key
 * @returns {Array}
 * @constructor
 */
function objectGetValuesByKey(obj, key)
{
    var items = [];

    for (var index in obj)
    {
        if (obj.hasOwnProperty(index))
        {
            if (key in obj[index])
            {
                items.push(obj[index][key]);
            }
        }
    }

    return items;
};