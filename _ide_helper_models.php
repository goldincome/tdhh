<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Admin
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $profile_url
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereProfileUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Admin extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BookingFee
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BookingFee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookingFee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookingFee query()
 * @mixin \Eloquent
 */
	class BookingFee extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BookingPayment
 *
 * @property string $uuid
 * @property string $booking_id
 * @property int $payment_method_id
 * @property string|null $amount_paid
 * @property string|null $amount_outstanding
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BookingPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookingPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookingPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|BookingPayment whereAmountOutstanding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingPayment whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingPayment whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingPayment wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingPayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingPayment whereUuid($value)
 * @mixin \Eloquent
 */
	class BookingPayment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Bookings
 *
 * @property int $id
 * @property string $property_id
 * @property string $user_id
 * @property string|null $proposed_check_in_date
 * @property string|null $proposed_check_in_time
 * @property string|null $proposed_check_out_date
 * @property string|null $proposed_check_out_time
 * @property string|null $actual_check_in_date
 * @property string|null $actual_check_in_time
 * @property string|null $actual_check_out_date
 * @property string|null $actual_check_out_time
 * @property string|null $adult
 * @property string|null $children
 * @property string|null $pet
 * @property string|null $message
 * @property int $booking_status_id
 * @property int $payment_status_id
 * @property string|null $payment_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings whereActualCheckInDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings whereActualCheckInTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings whereActualCheckOutDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings whereActualCheckOutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings whereAdult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings whereBookingStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings whereChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings wherePaymentStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings wherePet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings whereProposedCheckInDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings whereProposedCheckInTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings whereProposedCheckOutDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings whereProposedCheckOutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookings whereUserId($value)
 * @mixin \Eloquent
 */
	class Bookings extends \Eloquent implements \Prettus\Repository\Contracts\Transformable {}
}

namespace App\Models{
/**
 * App\Models\Partner
 *
 * @property int $id
 * @property string $name
 * @property string|null $business_name
 * @property string $email
 * @property string|null $phone
 * @property int $partner_type
 * @property string|null $parent_id
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $profile_url
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Partner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Partner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Partner query()
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner wherePartnerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereProfileUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Partner extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Property
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $price
 * @property string $address
 * @property int|null $bedroom_num
 * @property int|null $bathroom_num
 * @property int|null $max_occupant_num
 * @property string|null $city
 * @property int $country_id
 * @property int $state_id
 * @property string|null $characteristics
 * @property string $listed_by
 * @property string|null $map_address
 * @property int $status_id
 * @property string|null $facilities
 * @property string|null $house_rules
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Property newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Property newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Property query()
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereBathroomNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereBedroomNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereCharacteristics($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereFacilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereHouseRules($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereListedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereMapAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereMaxOccupantNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Property extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PropertyFacility
 *
 * @property int $id
 * @property string $name
 * @property string|null $icon
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyFacility newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyFacility newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyFacility query()
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyFacility whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyFacility whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyFacility whereName($value)
 * @mixin \Eloquent
 */
	class PropertyFacility extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PropertyImage
 *
 * @property int $id
 * @property string $property_id
 * @property string $image_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyImage whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyImage wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class PropertyImage extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $profile_url
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfileUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

