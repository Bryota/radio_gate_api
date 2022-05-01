<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\DataProviders\Models{
/**
 * App\DataProviders\Models\Listener
 *
 * @property int $id
 * @property string|null $last_name
 * @property string|null $first_name
 * @property string|null $last_name_kana
 * @property string|null $first_name_kana
 * @property string|null $radio_name
 * @property int|null $post_code
 * @property string|null $prefecture
 * @property string|null $city
 * @property string|null $house_number
 * @property string|null $building
 * @property string|null $room_number
 * @property string|null $tel
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DataProviders\Models\ListenerMyProgram[] $ListenerMyPrograms
 * @property-read int|null $listener_my_programs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DataProviders\Models\RequestFunctionListenerSubmit[] $RequestFunctionListenerSubmits
 * @property-read int|null $request_function_listener_submits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DataProviders\Models\RequestFunction[] $RequestFunctions
 * @property-read int|null $request_functions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DataProviders\Models\MessageTemplate[] $messageTemplates
 * @property-read int|null $message_templates_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\DataProviders\Models\ListenerFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Listener newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Listener query()
 * @method static \Illuminate\Database\Eloquent\Builder|Listener whereBuilding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener whereFirstNameKana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener whereHouseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener whereLastNameKana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener wherePostCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener wherePrefecture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener whereRadioName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener whereRoomNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener whereTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Listener whereUpdatedAt($value)
 */
	class Listener extends \Eloquent {}
}

namespace App\DataProviders\Models{
/**
 * App\DataProviders\Models\ListenerMessage
 *
 * @property int $id
 * @property int|null $radio_program_id
 * @property int|null $program_corner_id
 * @property int|null $listener_my_program_id
 * @property int|null $my_program_corner_id
 * @property int $listener_id
 * @property string|null $subject
 * @property string $content
 * @property string|null $radio_name
 * @property string|null $posted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\DataProviders\Models\Listener $Listener
 * @property-read \App\DataProviders\Models\ListenerMyProgram|null $ListenerMyProgram
 * @property-read \App\DataProviders\Models\MyProgramCorner|null $MyProgramCorner
 * @property-read \App\DataProviders\Models\ProgramCorner|null $ProgramCorner
 * @property-read \App\DataProviders\Models\RadioProgram|null $RadioProgram
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMessage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMessage whereListenerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMessage whereListenerMyProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMessage whereMyProgramCornerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMessage wherePostedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMessage whereProgramCornerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMessage whereRadioName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMessage whereRadioProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMessage whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMessage whereUpdatedAt($value)
 */
	class ListenerMessage extends \Eloquent {}
}

namespace App\DataProviders\Models{
/**
 * App\DataProviders\Models\ListenerMyProgram
 *
 * @property int $id
 * @property int $listener_id
 * @property string $program_name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DataProviders\Models\ListenerMessage[] $ListenerMessages
 * @property-read int|null $listener_messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DataProviders\Models\MyProgramCorner[] $MyProgramCorners
 * @property-read int|null $my_program_corners_count
 * @property-read \App\DataProviders\Models\Listener $listener
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMyProgram newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMyProgram newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMyProgram query()
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMyProgram whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMyProgram whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMyProgram whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMyProgram whereListenerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMyProgram whereProgramName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListenerMyProgram whereUpdatedAt($value)
 */
	class ListenerMyProgram extends \Eloquent {}
}

namespace App\DataProviders\Models{
/**
 * App\DataProviders\Models\MessageTemplate
 *
 * @property int $id
 * @property int $listener_id
 * @property string $name
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\DataProviders\Models\Listener $listener
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereListenerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereUpdatedAt($value)
 */
	class MessageTemplate extends \Eloquent {}
}

namespace App\DataProviders\Models{
/**
 * App\DataProviders\Models\MyProgramCorner
 *
 * @property int $id
 * @property int $listener_my_program_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DataProviders\Models\ListenerMessage[] $ListenerMessages
 * @property-read int|null $listener_messages_count
 * @property-read \App\DataProviders\Models\ListenerMyProgram $ListenerMyProgram
 * @method static \Illuminate\Database\Eloquent\Builder|MyProgramCorner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MyProgramCorner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MyProgramCorner query()
 * @method static \Illuminate\Database\Eloquent\Builder|MyProgramCorner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MyProgramCorner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MyProgramCorner whereListenerMyProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MyProgramCorner whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MyProgramCorner whereUpdatedAt($value)
 */
	class MyProgramCorner extends \Eloquent {}
}

namespace App\DataProviders\Models{
/**
 * App\DataProviders\Models\ProgramCorner
 *
 * @property int $id
 * @property int $radio_program_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DataProviders\Models\ListenerMessage[] $ListenerMessages
 * @property-read int|null $listener_messages_count
 * @property-read \App\DataProviders\Models\RadioProgram $radioProgram
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramCorner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramCorner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramCorner query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramCorner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramCorner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramCorner whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramCorner whereRadioProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramCorner whereUpdatedAt($value)
 */
	class ProgramCorner extends \Eloquent {}
}

namespace App\DataProviders\Models{
/**
 * App\DataProviders\Models\RadioProgram
 *
 * @property int $id
 * @property int $radio_station_id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DataProviders\Models\ListenerMessage[] $ListenerMessages
 * @property-read int|null $listener_messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DataProviders\Models\ProgramCorner[] $ProgramCorners
 * @property-read int|null $program_corners_count
 * @property-read \App\DataProviders\Models\RadioStation $radioStation
 * @method static \Illuminate\Database\Eloquent\Builder|RadioProgram newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RadioProgram newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RadioProgram query()
 * @method static \Illuminate\Database\Eloquent\Builder|RadioProgram whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadioProgram whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadioProgram whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadioProgram whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadioProgram whereRadioStationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadioProgram whereUpdatedAt($value)
 */
	class RadioProgram extends \Eloquent {}
}

namespace App\DataProviders\Models{
/**
 * App\DataProviders\Models\RadioStation
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DataProviders\Models\RadioProgram[] $radioPrograms
 * @property-read int|null $radio_programs_count
 * @method static \Illuminate\Database\Eloquent\Builder|RadioStation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RadioStation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RadioStation query()
 * @method static \Illuminate\Database\Eloquent\Builder|RadioStation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadioStation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadioStation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadioStation whereUpdatedAt($value)
 */
	class RadioStation extends \Eloquent {}
}

namespace App\DataProviders\Models{
/**
 * App\DataProviders\Models\RequestFunction
 *
 * @property int $id
 * @property int $listener_id
 * @property string $name
 * @property string $detail
 * @property int $point
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DataProviders\Models\RequestFunctionListenerSubmit[] $RequestFunctionListenerSubmits
 * @property-read int|null $request_function_listener_submits_count
 * @property-read \App\DataProviders\Models\Listener $listener
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunction query()
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunction whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunction whereListenerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunction whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunction wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunction whereUpdatedAt($value)
 */
	class RequestFunction extends \Eloquent {}
}

namespace App\DataProviders\Models{
/**
 * App\DataProviders\Models\RequestFunctionListenerSubmit
 *
 * @property int $id
 * @property int $listener_id
 * @property int $request_function_id
 * @property int $point
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\DataProviders\Models\RequestFunction $RequestFunction
 * @property-read \App\DataProviders\Models\Listener $listener
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunctionListenerSubmit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunctionListenerSubmit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunctionListenerSubmit query()
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunctionListenerSubmit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunctionListenerSubmit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunctionListenerSubmit whereListenerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunctionListenerSubmit wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunctionListenerSubmit whereRequestFunctionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestFunctionListenerSubmit whereUpdatedAt($value)
 */
	class RequestFunctionListenerSubmit extends \Eloquent {}
}

