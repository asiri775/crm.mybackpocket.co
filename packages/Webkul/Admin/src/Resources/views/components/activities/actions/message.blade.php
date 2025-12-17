<?php
$user = null;
if(isset($person) && $person!=null){
    $emails = $person->emails;
    if($emails!=null && count($emails) > 0){
        foreach ($emails as $email) {
            if(is_array($email) && array_key_exists('value', $email)){
                $email = $email['value'];
                $userModel = \App\Models\User::where('email', $email)->first();
                if($userModel!=null){
                    $user = $userModel;
                }
            }
        }
    }   
}
if($user!=null){
?>
<div class="flex">
    <a href="{{ route('user.chat.init', $user->id) }}"
        class="flex h-[74px] w-[84px] flex-col items-center justify-center gap-1 rounded-lg border border-transparent bg-primary font-medium text-green-900 transition-all hover:border-green-400">
        <span class="icon-mail text-2xl dark:!text-green-900"></span>
        Message
    </a>
</div>
<?php }?>
