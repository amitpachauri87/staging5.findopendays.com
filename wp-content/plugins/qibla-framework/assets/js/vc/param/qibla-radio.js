;(function (_, window)
{
    "use strict";

    /**
     * Qibla Radio Button
     *
     * @since 1.6.0
     *
     * @type {{parse: vc.atts.qibla_radio.parse, defaults: vc.atts.qibla_radio.defaults}}
     */
    window.vc.atts.qibla_radio = {
        /**
         * Used to save multiple values in single string for saving/parsing/opening
         * @param param
         * @returns {string}
         */
        parse: function (param)
        {
            var newValue = '';

            _.forEach(this.content()[0].querySelectorAll('input[name=' + param.param_name + ']'), function (el)
            {
                if (el.checked) {
                    newValue = el.value;
                }
            });

            return newValue;
        },
        /**
         * Used in shortcode saving
         * Default: '' empty (unchecked)
         * Can be overwritten by 'std'
         * @param param
         * @returns {string}
         */
        defaults: function (param)
        {
            return '';
        }
    };
})(_, window);