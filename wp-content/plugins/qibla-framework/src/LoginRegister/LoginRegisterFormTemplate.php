<?php
/**
 * LoginRegisterFormFacade
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

namespace QiblaFramework\LoginRegister;

use QiblaFramework\TemplateEngine\Engine;

/**
 * Class LoginRegisterFormFacade
 *
 * @since   1.5.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaFramework\LoginRegister
 */
class LoginRegisterFormTemplate
{
    /**
     * Get Data for Template
     *
     * @since  1.5.0
     *
     * @return \stdClass
     */
    public function getData()
    {
        // Initialize Data.
        $data = new \stdClass();

        return $data;
    }

    /**
     * Login Register Form Template
     *
     * @since  1.5.0
     *
     * @param \stdClass $data The data to consume within the template.
     *
     * @return void
     */
    public function loginRegisterFormTmpl(\stdClass $data)
    {
        $engine = new Engine('login_register_form', $data, '/views/form/loginRegisterForm.php');
        $engine->render();
    }

    /**
     * Helper
     *
     * @since  1.5.0
     *
     * @return LoginRegisterFormTemplate The instance of the class
     */
    public static function loginRegisterFormHelper()
    {
        $instance = new static;
        $instance->loginRegisterFormTmpl(
            $instance->getData()
        );
    }
}
