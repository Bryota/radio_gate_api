<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\DataProviders\Models\RadioStation;
use App\DataProviders\Models\RadioProgram;
use App\DataProviders\Models\ProgramCorner;
use App\DataProviders\Models\Listener;
use App\DataProviders\Models\ListenerMyProgram;
use App\DataProviders\Models\MyProgramCorner;
use App\DataProviders\Models\MessageTemplate;
use App\DataProviders\Models\RequestFunction;
use App\DataProviders\Models\RequestFunctionListenerSubmit;
use App\DataProviders\Models\ListenerMessage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // TODO: リファクタ
        Listener::factory()
            ->count(10)
            ->create()
            ->each(function ($listener) {
                $this->listener_id = $listener->id;

                ListenerMyProgram::factory(['listener_id' => $this->listener_id])
                    ->count(5)
                    ->create()
                    ->each(function ($program) {
                        MyProgramCorner::factory(['listener_my_program_id' => $program->id])
                            ->count(5)
                            ->create();
                    });

                MessageTemplate::factory(['listener_id' => $this->listener_id])
                    ->count(5)
                    ->create();

                RequestFunction::factory(['listener_id' => $this->listener_id])
                    ->count(5)
                    ->create()
                    ->each(function ($request_function) {
                        RequestFunctionListenerSubmit::factory(['listener_id' => $request_function->listener_id, 'request_function_id' => $request_function->id])
                            ->count(1)
                            ->create();
                    });

                ListenerMyProgram::factory(['listener_id' => $this->listener_id])
                    ->count(5)
                    ->create()
                    ->each(function ($program) {
                        MyProgramCorner::factory(['listener_my_program_id' => $program->id])
                            ->count(5)
                            ->create()
                            ->each(function ($corner) {
                                ListenerMessage::factory(['listener_id' => $this->listener_id, 'listener_my_program_id' => $corner->listener_my_program_id, 'my_program_corner_id' => $corner->id])
                                    ->count(5)
                                    ->create();
                            });
                    });

                RadioStation::factory()
                    ->count(5)
                    ->create()
                    ->each(function ($station) {
                        RadioProgram::factory(['radio_station_id' => $station->id])
                            ->count(5)
                            ->create()
                            ->each(function ($program) {
                                ProgramCorner::factory(['radio_program_id' => $program->id])
                                    ->count(5)
                                    ->create()
                                    ->each(function ($corner) {
                                        ListenerMessage::factory(['listener_id' => $this->listener_id, 'radio_program_id' => $corner->radio_program_id, 'program_corner_id' => $corner->id])
                                            ->count(5)
                                            ->create();
                                    });
                            });
                    });
            });
    }
}
