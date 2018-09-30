
class Color {

    /**
     * @param alpha
     * @returns {string}
     */
    static generateRGBColor(alpha = 1)
    {
        var red = this.rand(0, 255);
        var green = this.rand(0, 255);
        var blue = this.rand(0, 255);

        return "rgba("+ red.toString() +","+ green.toString() +","+ blue.toString() +","+ ((alpha > 1 || alpha < 0) ? 1 : alpha).toString() + ")";
    }

    /**
     * @returns {string}
     */
    static generateHEXColor()
    {
        var letters = '789ABCD'.split('');
        var color = '#';

        for (var i = 0; i < 6; i++)
        {
            color += letters[Math.round(Math.random() * 6)];
        }

        return color;
    }

    /**
     * @param getter
     * @param quantity
     * @returns {Array}
     */
    static getColorsArray(getter, quantity)
    {
        var colors = [];

        for (var i = 0; i < quantity; i++)
        {
            colors.push(getter());
        }

        return colors;
    }

    /**
     * @param min
     * @param max
     * @returns {*}
     */
    static rand(min, max)
    {
        if (min > max) {

            var temp = max;
            max = min;
            min = temp;

        }

        return Math.random() * (min - max) + min;
    }

}