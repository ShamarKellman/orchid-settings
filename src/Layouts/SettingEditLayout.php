<?php

namespace ShamarKellman\Settings\Layouts;

use Orchid\Screen\Fields\Code;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class SettingEditLayout extends Rows
{
    private bool $edit;

    public function __construct(bool $edit)
    {
        $this->edit = $edit;
    }

    public function fields(): array
    {
        $fields = [
            'key' => Input::make('setting.key')
                ->required()
                ->readonly($this->edit)
                ->max(255)
                ->title(__('Key')),

            'title' => Input::make('setting.options.title')
                ->required()
                ->max(255)
                ->title(__('Title')),

            'desc' => TextArea::make('setting.options.desc')
                ->row(5)
                ->title(__('Description')),

            'type' => Select::make('setting.options.type')
                ->readonly($this->edit)
                ->options([
                    'input' => 'Input',
                    'textarea' => 'Textarea',
                    'picture' => 'Picture',
                    'code' => 'CodeEditor (JSON)',
                    'codejs' => 'CodeEditor (JavaScript)',
                ])
                ->title(__('Type')),
        ];

        if (! is_null($this->query->getContent('setting.options.type'))) {
            $type = $this->query->getContent('setting.options.type');
        } elseif (is_array($this->query->getContent('setting.value'))) {
            $type = 'code';
        } else {
            $type = 'input';
        }

        switch ($type) {

            case 'picture':
                $fields['width'] = Input::make('setting.value.width')
                    ->title('Picture width');
                $fields['height'] = Input::make('setting.value.height')
                    ->title('Picture height');
                $fields['value'] = Picture::make('setting.value.value')
                    ->width($this->query->getContent('setting.value.width') ?? 500)
                    ->height($this->query->getContent('setting.value.height') ?? 300);

                break;
            case 'code':
                $fields['value'] = Code::make('setting.value')
                    ->language('json')
                    ->title(__('Value code'));

                break;
            case 'codejs':
                $fields['value'] = Code::make('setting.value')
                    ->language('js')
                    ->title(__('Value code'));

                break;
            case 'textarea':
                $fields['value'] = TextArea::make('setting.value')
                    ->title(__('Value'));

                break;
            default:
                $fields['value'] = Input::make('setting.value')
                    ->title(__('Value'));
        }

        return $fields;
    }
}
