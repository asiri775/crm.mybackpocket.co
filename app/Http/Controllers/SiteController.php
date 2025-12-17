<?php

namespace App\Http\Controllers;

use App\Helpers\ChatHelper;
use App\Helpers\CodeHelper;
use App\Helpers\Constants;
use App\Models\LoginRequests;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Webkul\Contact\Models\Person;
use Webkul\Lead\Models\Lead;

class SiteController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function authLogin()
    {
        $token = isset($_GET['token']) ? $_GET['token'] : "";
        $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : "/admin/mail/inbox";
        if ($token != "") {
            $loginRequest = LoginRequests::getRequest($token);
            if ($loginRequest != null) {
                $user = User::where('id', $loginRequest->user_id)->first();
                if ($user != null) {
                    //mark user as active and role as role from myoffice and login him
                    $isAdmin = CodeHelper::isAdmin($user);
                    $isVendor = CodeHelper::isVendor($user);

                    if ($isAdmin) {
                        $user->role_id = Constants::ADMIN_ROLE;
                    } elseif ($isVendor) {
                        $user->role_id = Constants::HOST_ROLE;
                    } else {
                        $user->role_id = Constants::GUEST_ROLE;
                    }

                    $user->user_status = 1;
                    $user->save();
                    //login
                    Auth::login($user);
                    //delete request
                    // $loginRequest->delete();
                    return redirect()->to($redirect);
                } else {
                    return redirect(env('MAIN_APP_URL') . "?error=" . urlencode('Invalid User'));
                }
            } else {
                return redirect(env('MAIN_APP_URL') . "?error=" . urlencode('Token expired or invalid'));
            }
        } else {
            return redirect(env('MAIN_APP_URL') . "?error=" . urlencode('Invalid Token'));
        }
    }

    public function createLead(Request $request)
    {
        $inputs = $request->input();
        $extraData = $request->input('extraData');
        $receiverId = $request->input('receiver_id');
        if ($receiverId != null) {
            $user = User::where('id', $receiverId)->first();
            if ($user != null) {
                Auth::login($user);
                $email = $request->input('email');
                $contactPerson = CodeHelper::createPerson($user, [
                    'name' => $request->input('name'),
                    'email' => $email,
                    'mobile_no' => $request->input('mobile_no')
                ]);
                if ($contactPerson != null) {

                    $domain = null;
                    $referer = array_key_exists('referer', $extraData) ? $extraData['referer'] : null;
                    if ($referer != null) {
                        $parsedUrl = parse_url($referer);
                        $domain = $parsedUrl['host'] ?? null;
                    }

                    $spaceId = array_key_exists('spaceId', $extraData) ? $extraData['spaceId'] : null;

                    $leadData = [
                        "title" => $request->input('title'),
                        "description" => $request->input('description'),
                        "status" => 1,
                        "lead_source_id" => 1,
                        "lead_type_id" => 2,
                        "lead_pipeline_id" => 1,
                        "lead_pipeline_stage_id" => 1,
                        "user_id" => $user->id,
                        "person_id" => $contactPerson->id,
                        "expected_close_date" => "0000-00-00",
                        "referer" => $referer,
                        "domain" => $domain,
                        "space_id" => $spaceId
                    ];
                    //create lead
                    $lead = new Lead($leadData);
                    if ($lead->save()) {
                        echo "Lead saved successfully";
                        die;
                    }
                } else {
                    echo "Contact Person not found";
                    die;
                }
            } else {
                echo "User not found";
                die;
            }
        } else {
            echo "Receiver not found";
            die;
        }
    }

    public function createChatMessage(Request $request)
    {
        $userId = $request->input('userId');
        $message = $request->input('message');
        $receiverId = $request->input('receiverId');

        if ($userId != null && $message != null && $receiverId != null) {

            $user = User::where('id', $userId)->first();
            $receiver = User::where('id', $receiverId)->first();

            if ($user != null && $receiver != null) {
                Auth::login($user);

                //initialize chat
                $chat = ChatHelper::createOrFindChat(
                    Constants::CHAT_TYPE_NORMAL,
                    $user->id,
                    $receiver->id
                );

                //send message
                $chatMessage = ChatHelper::sendChatMessage($chat->id, $user->id, $message);

                echo "Message sent successfully";
                die;

            } else {
                echo "User and Receiver not found";
                die;
            }
        } else {
            echo "Data not found";
            die;
        }
    }

    public function searchUsers()
    {
        $query = isset($_GET['query']) ? trim($_GET['query']) : null;
        $data = [];
        if ($query != null) {
            $users = User::query()
                ->where('name', 'LIKE', "%{$query}%")
                ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$query}%")
                ->limit(15)->get();
            if ($users != null) {
                foreach ($users as $user) {
                    $fullName = $user->first_name;
                    if ($user->last_name != null) {
                        $fullName .= " " . $user->last_name;
                    }
                    $data[] = [
                        'link' => route('user.chat.init', $user->id),
                        'name' => $user->name . " (" . $fullName . ")"
                    ];
                }
            }
            return response()->json(['status' => 'success', 'items' => $data]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Enter something to search.']);
        }
    }

}
