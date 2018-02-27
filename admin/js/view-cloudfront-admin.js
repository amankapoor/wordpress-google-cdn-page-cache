jQuery(function(a) {
    a("#menu-settings, #toplevel_page_o10n").length && a('#menu-settings li a[href="options-general.php?page=o10n-google-cdn"], #toplevel_page_o10n li a[href="admin.php?page=o10n-google-cdn"]').html('<img src="' + o10n_google_cdn_dir + 'admin/images/google-cloud.svg" width="16" height="16" align="absmiddle" title="AWS Google CDN Page Cache" style="margin-right:2px;"> Google CDN');
    a("#publish").length && a("#publish").closest("#major-publishing-actions").length && a("#google_cdn_invalidate_container").length &&
        (a("#publish").closest("#major-publishing-actions").append(a("#google_cdn_invalidate_container")), a("#google_cdn_invalidate_container").show(), a("#google_cdn_invalidate_container a.action").on("click", function() {
            a(this).hide();
            a("#google-cdn-select").show();
            a("#google-cdn-select select").focus()
        }), a('#google_cdn_invalidate_container select[name="o10n_google_cdn_purge"]').on("change", function() {
            a("#google-cdn-save-default").show()
        }))
});