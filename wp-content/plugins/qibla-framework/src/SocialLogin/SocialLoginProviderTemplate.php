<?php
/**
 * Social Login Provider Template
 *
 * @author    Guido Scialfa <dev@guidoscialfa.com>
 * @copyright Copyright (c) 2017, Guido Scialfa
 * @license   GNU General Public License, version 2
 *
 * Copyright (C) 2017 Guido Scialfa <dev@guidoscialfa.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace QiblaFramework\SocialLogin;

use QiblaFramework\TemplateEngine\Engine;
use QiblaFramework\TemplateEngine\TemplateInterface;

/**
 * Class SocialLoginProviderTemplate
 *
 * @since   1.6.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\SocialLogin
 */
class SocialLoginProviderTemplate implements TemplateInterface
{
    /**
     * Provider name
     *
     * @since 1.6.0
     *
     * @var string The social provider. Facebook, Twitter etc...
     */
    private $providerName;

    /**
     * Provider ID
     *
     * The slug version of the provider name
     *
     * @since 1.6.0
     *
     * @var string The slug version of the provider name
     */
    private $providerID;

    /**
     * Authenticate Url
     *
     * @since 1.6.0
     *
     * @var string The url where the user will be authenticated
     */
    private $authenticateUrl;

    /**
     * SocialLoginProviderTemplate constructor
     *
     * @since 1.6.0
     *
     * @param string $providerID      The slug version of the provider name.
     * @param string $providerName    The social provider. Facebook, Twitter etc...
     * @param string $authenticateUrl The url where the user will be authenticated.
     */
    public function __construct($providerID, $providerName, $authenticateUrl)
    {
        $this->providerID      = sanitize_key($providerID);
        $this->providerName    = $providerName;
        $this->authenticateUrl = $authenticateUrl;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return (object)array(
            'providerID'      => $this->providerID,
            'providerName'    => $this->providerName,
            'authenticateUrl' => $this->authenticateUrl,
        );
    }

    /**
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new Engine('social_login', $data, '/views/socialLogin/provider.php');
        $engine->render();
    }

    /**
     * Filter Social Login Provider
     *
     * The logic works only if hooke in `wsl_render_auth_widget_alter_provider_icon_markup` filter.
     *
     * @since 1.6.0
     *
     * @param string $providerID      The slug version of the provider name.
     * @param string $providerName    The social provider. Facebook, Twitter etc...
     * @param string $authenticateUrl The url where the user will be authenticated.
     */
    public static function filterSocialLoginProviderFilter($providerID, $providerName, $authenticateUrl)
    {
        if ('wsl_render_auth_widget_alter_provider_icon_markup' === current_filter()) {
            $instance = new static($providerID, $providerName, $authenticateUrl);
            $instance->tmpl($instance->getData());
        }
    }
}
