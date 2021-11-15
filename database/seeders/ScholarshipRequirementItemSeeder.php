<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementItemOption;

class ScholarshipRequirementItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requirements = ScholarshipRequirement::all();

        $items_data = [
            [
                'item' => 'Certificate of Registration',
                'note' => 'Upload in PDF file',
                'type' => 'cor',
            ],
            [
                'item' => 'No. of units taken',
                'note' => 'Specify the current no. of units taken',
                'type' => 'units',
            ],
            [
                'item' => 'Previuos Semester Grades',
                'note' => 'Upload in PDF file',
                'type' => 'grade',
            ],
            [
                'item' => 'GWA of previous semester',
                'note' => 'GWA in 1, 1.25, ..., 5 format only',
                'type' => 'gwa',
            ],
            [
                'item' => 'Back to back ID Picture',
                'note' => 'Upload in PDF file',
                'type' => 'file',
            ],
            [
                'item' => 'Regular Student',
                'note' => '',
                'type' => 'radio',
                'options' => [
                    [
                        'option' => 'Yes',
                    ],
                    [
                        'option' => 'No',
                    ],
                ],
            ],
            [
                'item' => 'Scholar Information',
                'note' => 'Check all applied options',
                'type' => 'check',
                'options' => [
                    [
                        'option' => 'Renting a dormitory/house/apartment',
                    ],
                    [
                        'option' => 'Registered voter',
                    ],
                    [
                        'option' => 'Limited data/Low bandwidth internet access',
                    ],
                    [
                        'option' => 'None',
                    ],
                ],
            ],
        ];

        foreach ($requirements as $requirement) {
            $requirement_id = $requirement->id;
            foreach ($items_data as $key => $item_data) {
                $item_COR = new ScholarshipRequirementItem;
                $item_COR->requirement_id  = $requirement_id;
                $item_COR->item = $item_data['item'];
                $item_COR->note = $item_data['note'];
                $item_COR->type = $item_data['type'];
                $item_COR->position = $key+1;
                $item_COR->save();

                if ( isset($item_data['options']) ) {
                    $item_id = $item_COR->id;
                    foreach ($item_data['options'] as $key => $option) {
                        $item_option = new ScholarshipRequirementItemOption;
                        $item_option->item_id = $item_id;
                        $item_option->option  = $option['option'];
                        $item_option->save();
                    }
                }
            }
        }
    }
}
