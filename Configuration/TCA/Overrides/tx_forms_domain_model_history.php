<?php

/* 
 * Copyright (C) 2021 PARAT Beteiligungs GmbH
 * Markus Blöchl <mbloechl@parat.eu>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

// Configure new fields 
$fields = [
    'assigned_training' => [
        'exclude' => 1,
        'label' => 'Training',
        'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
        ],
    ],
];