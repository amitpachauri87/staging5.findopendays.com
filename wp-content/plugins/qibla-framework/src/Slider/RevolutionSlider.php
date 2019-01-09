<?php
/**
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    Qibla\Slider
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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

namespace QiblaFramework\Slider;

/**
 * Class RevolutionSlider
 *
 * @since   1.6.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package Qibla\Slider
 */
class RevolutionSlider
{
    /**
     * The slider Instance
     *
     * @since  1.0.0
     *
     * @var \RevSliderSlider The internal slider instance
     */
    private $slider;

    /**
     * Sliders List
     *
     * @since  1.0.0
     */
    private $slidersList;

    /**
     * Set Sliders list
     *
     * @since  1.0.0
     *
     * @return void
     */
    protected function setSliderList()
    {
        $this->slidersList = $this->slider->getArrSliders();
    }

    /**
     * RevolutionSlider constructor
     *
     * @since 1.0.0
     *
     * @param \RevSliderSlider $slider
     */
    public function __construct(\RevSliderSlider $slider)
    {
        // Set the slider Instance.
        $this->slider = $slider;
        // Set the list of the allowed Rev-slider.
        $this->setSliderList();
    }

    /**
     * Get Slider list
     *
     * @since  1.0.0
     *
     * @return array
     */
    public function getSliderList()
    {
        // No sliders found?
        if (empty($this->slidersList)) {
            return array();
        }

        $sliders = array();
        foreach ($this->slidersList as $list) {
            if ($this->slider->isAliasExists($list->getAlias())) {
                $sliders[sanitize_key($list->getAlias())] = ucwords($list->getTitle());
            }
        }

        return $sliders;
    }

    /**
     * Is Revolution Slider Plugin active
     *
     * @since 1.6.0
     *
     * @return bool True if plugin is active, false otherwise.
     */
    public static function sliderIsActive()
    {
        return class_exists('RevSliderSlider');
    }
}
