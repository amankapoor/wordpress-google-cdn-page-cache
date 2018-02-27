<?php

class PluginTest extends WP_UnitTestCase
{

  // Check that that activation doesn't break
    public function test_plugin_activated()
    {
        $this->assertTrue(is_plugin_active(PLUGIN_PATH));
    }

    // Check that public methods are available
    public function test_public_methods()
    {
        $this->assertTrue(
            function_exists('O10n\GoogleCDN\set_max_age')
            && function_exists('O10n\GoogleCDN\set_expire')
            && function_exists('O10n\GoogleCDN\nocache')
        );
    }
}
