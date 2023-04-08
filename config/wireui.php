<?php

use WireUi\View\Components;

return [
    /*
        |--------------------------------------------------------------------------
        | Icons
        |--------------------------------------------------------------------------
        |
        | The icons config will be used in icon component as default
        | https://heroicons.com
        |
    */
    'icons' => [
        'style' => env('WIREUI_ICONS_STYLE', 'outline'),
    ],

    /*
        |--------------------------------------------------------------------------
        | Modal
        |--------------------------------------------------------------------------
        |
        | The default modal preferences
        |
    */
    'modal' => [
        'zIndex'   => env('WIREUI_MODAL_Z_INDEX', 'z-50'),
        'maxWidth' => env('WIREUI_MODAL_MAX_WIDTH', '2xl'),
        'spacing'  => env('WIREUI_MODAL_SPACING', 'p-4'),
        'align'    => env('WIREUI_MODAL_ALIGN', 'start'),
        'blur'     => env('WIREUI_MODAL_BLUR', false),
    ],

    /*
        |--------------------------------------------------------------------------
        | Card
        |--------------------------------------------------------------------------
        |
        | The default card preferences
        |
    */
    'card' => [
        'padding'   => env('WIREUI_CARD_PADDING', 'px-2 py-5 md:px-4'),
        'shadow'    => env('WIREUI_CARD_SHADOW', 'shadow-md'),
        'rounded'   => env('WIREUI_CARD_ROUNDED', 'rounded-lg'),
        'color'     => env('WIREUI_CARD_COLOR', 'bg-white dark:bg-secondary-800'),
    ],

    /*
        |--------------------------------------------------------------------------
        | Components
        |--------------------------------------------------------------------------
        |
        | List with WireUI components.
        | Change the alias to call the component with a different name.
        | Extend the component and replace your changes in this file.
        | Remove the component from this file if you don't want to use.
        |
     */

    $componentAias = "wireui",

    'components' => [
        'avatar' => [
            'class' => Components\Avatar::class,
            'alias' => $componentAias . '-avatar',
        ],
        'icon' => [
            'class' => Components\Icon::class,
            'alias' => $componentAias . '-icon',
        ],
        'icon.spinner' => [
            'class' => Components\Icons\Spinner::class,
            'alias' => $componentAias . '-icon.spinner',
        ],
        'color-picker' => [
            'class' => Components\ColorPicker::class,
            'alias' => $componentAias . '-color-picker',
        ],
        'input' => [
            'class' => Components\Input::class,
            'alias' => $componentAias . '-input',
        ],
        'textarea' => [
            'class' => Components\Textarea::class,
            'alias' => $componentAias . '-textarea',
        ],
        'label' => [
            'class' => Components\Label::class,
            'alias' => $componentAias . '-label',
        ],
        'error' => [
            'class' => Components\Error::class,
            'alias' => $componentAias . '-error',
        ],
        'errors' => [
            'class' => Components\Errors::class,
            'alias' => $componentAias . '-errors',
        ],
        'inputs.maskable' => [
            'class' => Components\Inputs\MaskableInput::class,
            'alias' => $componentAias . '-inputs.maskable',
        ],
        'inputs.phone' => [
            'class' => Components\Inputs\PhoneInput::class,
            'alias' => $componentAias . '-inputs.phone',
        ],
        'inputs.currency' => [
            'class' => Components\Inputs\CurrencyInput::class,
            'alias' => $componentAias . '-inputs.currency',
        ],
        'inputs.number' => [
            'class' => Components\Inputs\NumberInput::class,
            'alias' => $componentAias . '-inputs.number',
        ],
        'inputs.password' => [
            'class' => Components\Inputs\PasswordInput::class,
            'alias' => $componentAias . '-inputs.password',
        ],
        'badge' => [
            'class' => Components\Badge::class,
            'alias' => $componentAias . '-badge',
        ],
        'badge.circle' => [
            'class' => Components\CircleBadge::class,
            'alias' => $componentAias . '-badge.circle',
        ],
        'button' => [
            'class' => Components\Button::class,
            'alias' => $componentAias . '-button',
        ],
        'button.circle' => [
            'class' => Components\CircleButton::class,
            'alias' => $componentAias . '-button.circle',
        ],
        'dropdown' => [
            'class' => Components\Dropdown::class,
            'alias' => $componentAias . '-dropdown',
        ],
        'dropdown.item' => [
            'class' => Components\Dropdown\DropdownItem::class,
            'alias' => $componentAias . '-dropdown.item',
        ],
        'dropdown.header' => [
            'class' => Components\Dropdown\DropdownHeader::class,
            'alias' => $componentAias . '-dropdown.header',
        ],
        'notifications' => [
            'class' => Components\Notifications::class,
            'alias' => $componentAias . '-notifications',
        ],
        'datetime-picker' => [
            'class' => Components\DatetimePicker::class,
            'alias' => $componentAias . '-datetime-picker',
        ],
        'time-picker' => [
            'class' => Components\TimePicker::class,
            'alias' => $componentAias . '-time-picker',
        ],
        'card' => [
            'class' => Components\Card::class,
            'alias' => $componentAias . '-card',
        ],
        'native-select' => [
            'class' => Components\NativeSelect::class,
            'alias' => $componentAias . '-native-select',
        ],
        'select' => [
            'class' => Components\Select::class,
            'alias' => $componentAias . '-select',
        ],
        'select.option' => [
            'class' => Components\Select\Option::class,
            'alias' => $componentAias . '-select.option',
        ],
        'select.user-option' => [
            'class' => Components\Select\UserOption::class,
            'alias' => $componentAias . '-select.user-option',
        ],
        'toggle' => [
            'class' => Components\Toggle::class,
            'alias' => $componentAias . '-toggle',
        ],
        'checkbox' => [
            'class' => Components\Checkbox::class,
            'alias' => $componentAias . '-checkbox',
        ],
        'radio' => [
            'class' => Components\Radio::class,
            'alias' => $componentAias . '-radio',
        ],
        'modal' => [
            'class' => Components\Modal::class,
            'alias' => $componentAias . '-modal',
        ],
        'modal.card' => [
            'class' => Components\ModalCard::class,
            'alias' => $componentAias . '-modal.card',
        ],
        'dialog' => [
            'class' => Components\Dialog::class,
            'alias' => $componentAias . '-dialog',
        ],
    ],
];
