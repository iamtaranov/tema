
/*** Element ***/

/*** Object ***/

/**
 * @param key
 * @returns {Array}

Object.prototype.getValuesByKey = function(key)
{
    var items = [];

    for (var index in this)
    {
        if (this.hasOwnProperty(index))
        {
            if (key in this[index])
            {
                items.push(this[index][key]);
            }
        }
    }

    return items;
};
 */

/*** Array ***/

/*
Array.prototype.keys = function()
{

};

Array.prototype.values = function()
{

};
*/

/**
 * @param event
 * @param callback
 */
Array.prototype.addEventListeners = function(event, callback)
{
    [].forEach.call(this, function(item)
    {
        item.addEventListener(event, function() { callback(item) });
    });
};

/**
 * @returns {number}
 */
Array.prototype.max = function()
{
    var max = parseInt(this[this.length - 1]), temp;

    for (var i = 0; i < this.length - 1; i++)
    {
        temp = parseInt(this[i]);

        if (temp > max) max = temp;
    }

    return max;
};

/**
 * @returns {number}
 */
Array.prototype.min = function()
{
    var min = parseInt(this[this.length - 1]), temp;

    for (var i = 0; i < this.length - 1; i++)
    {
        temp = parseInt(this[i]);

        if (temp < min) min = temp;
    }

    return min;
};