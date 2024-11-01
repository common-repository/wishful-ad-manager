/**
 * IIFE jQuery scripts.
 */
(function ($) {

    /**
     * Load the codes on document ready.
     */
    $(function ($) {

        wishfulAdManagerInitCodeMirror();

        wishfulAdManagerFeaturedImageBox();

        wishfulAdManagerInitTabSync();

        wishfulAdManagerToggleCustomScripts();

    });

    function wishfulAdManagerInitCodeMirror() {
        var headerScriptsEl = $('#header-scripts');
        var footerScriptsEl = $('#footer-scripts');

        if ( headerScriptsEl.length > 0 && footerScriptsEl.length > 0 ) {
            wp.codeEditor.initialize(headerScriptsEl, wishfulAdManagerCMSettings.editorHeaderScripts);
            wp.codeEditor.initialize(footerScriptsEl, wishfulAdManagerCMSettings.editorFooterScripts);
        }
    }

    /**
     * Cut the featured image box and paste it in our custom meta box.
     */
    function wishfulAdManagerFeaturedImageBox() {
        var featuredImageBox = $('#postimagediv');
        featuredImageBox.appendTo('#ad-banner-box-wrapper');
        featuredImageBox.find('button.handlediv, h2.hndle.ui-sortable-handle').remove();
    }


    /**
     * Displays the content box according to the tab head clicked.
     */
    function wishfulAdManagerInitTabSync() {
        $(document).on('click', '#wishful-ad-manager-tab-heads .nav-tab', function (e) {
            e.preventDefault();
            var tabContentID = $(this).attr('data-tab-content');
            var tabContent = document.getElementById(tabContentID);
            $('#tab-contents-wrapper .tab-content').addClass('hidden');
            $('#wishful-ad-manager-tab-heads .nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            $(tabContent).removeClass('hidden');
        });
        $('#wishful-ad-manager-tab-heads .nav-tab:first').trigger('click');
    }


    /**
     * Toggles the Ad Contents tab custom script box.
     */
    function wishfulAdManagerToggleCustomScripts() {
        $(document).on('change', '#use-custom-scripts', function () {
            var isChecked = $(this).is(':checked');
            if (!isChecked) {
                $('#custom-script-box-wrapper').hide();
                $('#ad-banner-box-wrapper').show();
            } else {
                $('#ad-banner-box-wrapper').hide();
                $('#custom-script-box-wrapper').show();
            }
        });
        $('#use-custom-scripts').trigger('change');
    }

})(jQuery);