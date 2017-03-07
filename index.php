<?php
/**
 * Created by @OnyxTM.
 * User: Morteza Bagher Telegram id : @mench
 * GitHub Url: https://github.com/onyxtm
 * Channel : @phpbots , @ch_jockdoni , @ch_pm , @onyxtm
 * Date: 11/12/2016
 * Time: 09:19 PM
 */
$admin = array("ادمین","ادمین2");

$update = json_decode(file_get_contents('php://input'));
$json = file_get_contents('php://input');
$txt = $update->message->text;
$chat_id = $update->message->chat->id;
$message_id = $update->message->message_id;
$reply = $update->message->reply_to_message;
$channel_forward = $update->channel_post->forward_from;
$channel_text = $update->channel_post->text;
$from = $update->message->from->id;
$chatid = $update->callback_query->message->chat->id;
$data = $update->callback_query->data;
$msgid = $update->callback_query->message->message_id;
$photo = $update->message->photo;
$name = $update->message->chat->first_name;
define("TOKEN","توکن");
$token = TOKEN;
$fileddd = json_decode(file_get_contents("https://api.telegram.org/bot$token/getMe"));
$id = $fileddd->result->id;
$st = file_get_contents("start.txt");

$ste = file_get_contents("step.txt");
$step = explode("\n",$ste);


$user = file_get_contents('Member.txt');
$members = explode("\n", $user);
if (!in_array($chat_id, $members)) {
    $add_user = file_get_contents('Member.txt');
    $add_user .= $chat_id . "\n";
    file_put_contents('Member.txt', $add_user);
}

//$photo[count($photo)-1]->file_id
function bridge($method, $datas=[]){
    $url = "https://api.telegram.org/bot".TOKEN."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($datas));
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}

function rp($Number){
    $Rand = substr(str_shuffle("UPLOADPVBOTuploadpvbot"), 0, $Number);
    return $Rand;
}

if(isset($update->message->video)) {
    $i = rand(100, 100000);
    $video = $update->message->video;
    $file = $video->file_id;
    $get = bridge('getfile', ['file_id' => $file]);
    $patch = $get->result->file_path;
    $av = file_get_contents("http://yeo.ir/api.php?url=http://up-pv.tk/u/m/$chat_id/$i.mp4&custom=UPV$i");
    $av2 = json_decode(file_get_contents("http://up-pv.tk/su/insert.php?url=http://up-pv.tk/u/m/$chat_id/$i.mp4"));
    file_put_contents("m/$chat_id/$i.mp4", file_get_contents('https://api.telegram.org/file/bot' . $token . '/' . $patch));
//    $aaa = insertLink("https://binaam.000webhostapp.com/u/m/$chat_id/$i.mp4");
    bridge("sendMessage", [
        'chat_id' => $chat_id,
        "text" => "فیلم شما آپلود شد 🔶
        لینک کوتاه شده 1:
$av
لینک اصلی:
http://up-pv.tk/u/m/$chat_id/$i.mp4
لینک کوتاه شده 2:
".$av2->url
    ]);

}elseif(isset($update->message->photo)) {
    $i = rand(100, 100000);
    $photo = $update->message->photo;
    $file = $photo[count($photo)-1]->file_id;
    $get = bridge('getfile', ['file_id' => $file]);
    $patch = $get->result->file_path;
    $ap = file_get_contents("http://yeo.ir/api.php?url=http://up-pv.tk/u/m/$chat_id/$i.png&custom=UPP$i");
    $ap2 = json_decode(file_get_contents("http://up-pv.tk/su/insert.php?url=http://up-pv.tk/u/m/$chat_id/$i.png"));

    file_put_contents("m/$chat_id/$i.png", file_get_contents('https://api.telegram.org/file/bot' . $token . '/' . $patch));
    bridge("sendMessage", [
        'chat_id' => $chat_id,
        "text" => "عکس شما آپلود شد 🔶
        لینک کوتاه شده:
$ap
لینک اصلی:
http://up-pv.tk/u/m/$chat_id/$i.png
لینک کوتاه شده 2:
".$ap2->url
    ]);
}elseif($txt == "/start"){
    $a = file_get_contents("index.txt");
    if(!is_dir("m/$chat_id")) {
        mkdir("m/$chat_id");
        file_put_contents("m/$chat_id/index.php",$a);
        file_put_contents("m/$chat_id/name.txt",$name);
    }else{
        file_put_contents("m/$chat_id/index.php",$a);
        file_put_contents("m/$chat_id/name.txt",$name);
    }
    if (in_array($chat_id,$admin)) {
        bridge("sendMessage", [
            'chat_id' => $chat_id,
            'text' => "$st",
            'reply_markup' => json_encode(['keyboard' => [
                [['text' => 'مدیریت']]
            ], 'resize_keyboard' => true
            ])
        ]);
    } else {
        bridge("sendMessage", [
            'chat_id' => $chat_id,
            'text' => "$st",
            'parse_mode' => "HTML",
            'reply_markup' => json_encode(['force_reply' => true,
                'selective' => true
            ])
        ]);
    }
} else if ($txt == "مدیریت" || $txt == "/manage" && in_array($chat_id,$admin)) {
    bridge("sendMessage", [
        'chat_id' => $chat_id,
        'text' => "به بخش مدیریت خوش آمدید :",
        'reply_markup' => json_encode(['keyboard' => [
            ["📊آمار کاربران"],
            ['فوروارد همگانی😉', "پیام همگانی ⌨"],
            ["⌨بازگشت به منوی اصلی⌨"]
        ], 'resize_keyboard' => true
        ])
    ]);
}else if ($txt == "فوروارد همگانی😉" && $step[0] == "NULL" && in_array($chat_id,$admin)) {
    bridge("sendMessage", [
        'chat_id' => $chat_id,
        'text' => "پیام خود را فوروارد کنید یا ارسال نمایید
برای لغو عملیات دستور /cancell را ارسال کنید",
        'reply_markup' => json_encode(['keyboard' => [
            [['text' => 'لغو عملیات']]
        ], 'resize_keyboard' => true])
    ]);
    file_put_contents("step.txt", "FORTOALL");

} elseif ($step[0] == "FORTOALL" && in_array($chat_id,$admin)) {
    if($txt == "لغو عملیات"){
        bridge("sendMessage", [
            'chat_id' => $chat_id,
            'text' => "پیام شما متن نمیباشد
برای لغو عملیات دستور /cancell را ارسال کنید",
            'reply_markup' => json_encode(['keyboard' => [
                ["📊آمار کاربران"],
                ['فوروارد همگانی😉', "پیام همگانی ⌨"],
                ["⌨بازگشت به منوی اصلی⌨"]
            ], 'resize_keyboard' => true])
        ]);
        file_put_contents("step.txt","NULL");
    }else {
        file_put_contents("step.txt", "NULL");
        $ttxttt = file_get_contents('Member.txt');
        $membersiddt = explode("\n", $ttxttt);
        bridge("sendMessage", [
            'chat_id' => $admin,
            "text" => "در صف ارسال ...",
            "parse_mode" => "HTML",
            'reply_markup' => json_encode(['keyboard' => [
                ["📊آمار کاربران"],
                ['فوروارد همگانی😉', "پیام همگانی ⌨"],
                ["⌨بازگشت به منوی اصلی⌨"]
            ], 'resize_keyboard' => true
            ])
        ]);
        for ($yt = 0; $yt < count($membersiddt); $yt++) {
            bridge("forwardmessage", [
                'chat_id' => $membersiddt[$yt],
                'from_chat_id' => $chat_id,
                'message_id' => $update->message->message_id
            ]);
        }

        $memcoutt = count($membersiddt) - 1;
        bridge("sendMessage", [
            'chat_id' => $admin,
            "text" => "پیام شما به $memcoutt نفر فوروارد شد.",
            "parse_mode" => "HTML",
            'reply_markup' => json_encode(['keyboard' => [
                ["📊آمار کاربران"],
                ['فوروارد همگانی😉', "پیام همگانی ⌨"],
                ["⌨بازگشت به منوی اصلی⌨"]
            ], 'resize_keyboard' => true
            ])
        ]);
    }
    //پیام همگانی ⌨
} elseif ($txt == "پیام همگانی ⌨" && in_array($chat_id,$admin) && $step[0] == "NULL") {
    file_put_contents("step.txt", "SENDTOALL");
    bridge("sendMessage", [
        'chat_id' => $chat_id,
        'text' => "پیام متنی خود را ارسال کنید
برای لغو عملیات دستور /cancell را ارسال کنید",
        'reply_markup' => json_encode(['keyboard' => [
            [['text' => 'لغو عملیات']]
        ], 'resize_keyboard' => true])
    ]);
} elseif ($step[0] == "SENDTOALL" && in_array($chat_id,$admin)) {
    if (isset($update->message->text)) {
        file_put_contents("step.txt", "NULL");
        $ttxtt = file_get_contents('Member.txt');
        $membersidd = explode("\n", $ttxtt);

        $memcout = count($membersidd) - 1;
        bridge("sendMessage", [
            'chat_id' => $admin,
            "text" => "در صف ارسال ...",
            "parse_mode" => "HTML",
            'reply_markup' => json_encode(['keyboard' => [
                ["📊آمار کاربران"],
                ['فوروارد همگانی😉', "پیام همگانی ⌨"],
                ["⌨بازگشت به منوی اصلی⌨"]
            ], 'resize_keyboard' => true
            ])
        ]);
        for ($y = 0; $y < count($membersidd); $y++) {
            bridge("sendMessage", [
                'chat_id' => $membersidd[$y],
                "text" => $txt,
                "parse_mode" => "HTML"
            ]);
        }

        bridge("sendMessage", [
            'chat_id' => $admin,
            "text" => "پیام شما به $memcout نفر ارسال شد.",
            "parse_mode" => "HTML",
            'reply_markup' => json_encode(['keyboard' => [
                ["📊آمار کاربران"],
                ['فوروارد همگانی😉', "پیام همگانی ⌨"],
                ["⌨بازگشت به منوی اصلی⌨"]
            ], 'resize_keyboard' => true
            ])
        ]);

        $to = "onyx.tm.mb@gmail.com";
        $subject = "پیام همگانی";
        $txt = "پیام:\r\n $txt
        \r\n
        ارسال شده به : $memcout";
        $headers = "From: mb@up-pv.tk" . "\r\n" .
            "CC: mb@up-pv.tk";


        mail($to,$subject,$txt,$headers);
    }elseif($txt == "لغو عملیات"){
        bridge("sendMessage", [
            'chat_id' => $chat_id,
            'text' => "پیام شما متن نمیباشد
برای لغو عملیات دستور /cancell را ارسال کنید",
            'reply_markup' => json_encode(['keyboard' => [
                ["📊آمار کاربران"],
                ['فوروارد همگانی😉', "پیام همگانی ⌨"],
                ["⌨بازگشت به منوی اصلی⌨"]
            ], 'resize_keyboard' => true])
        ]);
        file_put_contents("step.txt","NULL");
    } else {
        bridge("sendMessage", [
            'chat_id' => $chat_id,
            'text' => "پیام شما متن نمیباشد
برای لغو عملیات دستور /cancell را ارسال کنید",
            'reply_markup' => json_encode(['keyboard' => [
                [['text' => 'لغو عملیات']]
            ], 'resize_keyboard' => true])
        ]);
    }
} elseif($txt == "📊آمار کاربران" and in_array($chat_id,$admin)){
    $user = file_get_contents('Member.txt');
    $member_id = explode("\n", $user);
    $member_count = count($member_id) - 1;

    bridge('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "👥 تعداد کاربران جدید ربات شما : $member_count",
        'parse_mode' => 'HTML'
    ]);
    $to = "onyx.tm.mb@gmail.com";
    $subject = "تعداد اعضای ربات";
    $txt = "تعداد اعضای شما : $member_count";
    $headers = "From: mb@up-pv.tk" . "\r\n" .
        "CC: mb@up-pv.tk";


    mail($to,$subject,$txt,$headers);
}  else if ($txt == "⌨بازگشت به منوی اصلی⌨" && in_array($chat_id,$admin)) {
    bridge("sendMessage", [
        'chat_id' => $chat_id,
        'text' => "شما از پنل مدیریت خارج شدید",
        'reply_markup' => json_encode(['keyboard' => [
            array("مدیریت")
        ], 'resize_keyboard' => true
        ])
    ]);
}else{
    bridge("sendMessage", [
        'chat_id' => $chat_id,
        "text" => "پیام ارسالی عکس و یا فیلم جهت آپلود نمیباشد😉",
        'parse_mode'=>'Markdown'
    ]);
    file_put_contents("m/$chat_id/name.txt",$name);
}
