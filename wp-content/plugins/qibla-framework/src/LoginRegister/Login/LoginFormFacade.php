<?php
/**
 * LoginFormFacade
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

namespace QiblaFramework\LoginRegister\Login;

use QiblaFramework\Request\RequestFormFacadeInterface;
use QiblaFramework\TemplateEngine\Engine;

/**
 * Class LoginFormFacade
 *
 * @since   1.5.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\LoginRegister\Login
 */
class LoginFormFacade implements RequestFormFacadeInterface
{
    /**
     * Form
     *
     * The form instance
     *
     * @since  1.5.0
     *
     * @var LoginForm The login form instance
     */
    protected $form;

    /**
     * LoginFormFacade constructor
     *
     * @since 1.5.0
     */
    public function __construct()
    {
        $this->form = new LoginForm();
    }

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $login = new RequestFormLogin($this->form);
        $login->handleRequest();

        $self = $this;
        add_action('qibla_login_form', function () use ($self) {
            $self->formTmpl($self->getData());
        });
    }

    /**
     * Get Form
     *
     * @since  1.5.0
     *
     * @return LoginForm The form instance
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $data = new \stdClass();

        $data->form      = $this->form;
        $data->formTitle = esc_html__('Sign In', 'qibla-framework');

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function formTmpl(\stdClass $data)
    {
        $engine = new Engine('login_form', $data, '/views/form/loginForm.php');
        $engine->render();
    }

    /**
     * @inheritdoc
     */
    public static function getInstance()
    {
        static $instance = null;

        if (null === $instance) {
            $instance = new static;
        }

        return $instance;
    }
}
