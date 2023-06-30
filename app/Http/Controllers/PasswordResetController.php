<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    // halaman reset password 
    public function index()
    {
        return view('password-reset.index');
    }

    // email reset password 
    public function mailComposer(Request $request)
    {
        try {
            $token = md5($request->email) . rand(10, 9999);
            // buat token

            $expFormat = mktime(
                date("H") + 1,
                date("i"),
                date("s"),
                date("m"),
                date("d"),
                date("Y")
            );

            $expDate = date("Y-m-d H:i:s", $expFormat);
            // buat tokenya expire kapan

            // $update = mysqli_query($conn, "UPDATE users set  password='" . $password . "', reset_link_token='" . $token . "' ,exp_date='" . $expDate . "' WHERE email='" . $emailId . "'");

            $user = User::where('Email', $request->email)->first();
            $user->password_reset_token = $token;
            $user->exp_date = $expDate;
            $user->update();

            // $link = "<a href='www.yourwebsite.com/reset-password.php?key=" . $emailId . "&token=" . $token . "'>Click To Reset password</a>";

            // $link = "<a href=http://127.0.0.1:8000?key=" . $request->email . "&token=" . $token . "'>Click To Reset password</a>";

            $link = "http://127.0.0.1:8000/reset-password?key=" . $request->email . "&token=" . $token;


            require base_path("vendor/autoload.php");

            $mail = new PHPMailer(true);

            $mail->CharSet =  "utf-8";
            $mail->IsSMTP();
            // enable SMTP authentication
            $mail->SMTPAuth = true;
            // GMAIL username
            $mail->Username = "fashionstorebodong@gmail.com";
            // GMAIL password
            $mail->Password = "xmgnqxwgmgwclafm";
            $mail->SMTPSecure = "ssl";
            // sets GMAIL as the SMTP server
            $mail->Host = "smtp.gmail.com";
            // set the SMTP port for the GMAIL server
            $mail->Port = "465";

            $mail->From = 'fashionstorebodong@gmail.com';
            $mail->FromName = 'Fashion Store Bodong';
            $mail->AddAddress($user->email, $user->name);
            $mail->Subject  =  'Reset Password';
            $mail->IsHTML(true);
            $mail->Body    = 'Click On This Link to Reset Password ' . $link . ' , this link will be valid for 1 hour';

            if ($mail->Send()) {
                return back()->with('success', 'email has been sent');
            } else {
                return back()->with('error', $mail->ErrorInfo);
            }
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // reset password
    public function edit(Request $request)
    {
        $curDate = date("Y-m-d H:i:s");

        $user = User::where('email', $request->key)->where('password_reset_token', $request->token)->first();

        if ($user) {
            if (($user->exp_date) > $curDate) {
                return view('password-reset.edit', [
                    'user' => $user
                ]);
            } else {
                return 'page has expired';
            }
        }
    }

    public function update(Request $request)
    {
        // if ($request->Password != $request->passwordConfirmation) {
        //     return back()->with('error', 'password must be the same');
        // }

        $user = User::where('Email', $request->email)->where('password_reset_token', $request->token)->first();

        $rules = [
            'Password' => 'required|min:6|max:255|same:passwordConfirmation',
        ];

        $validatedData = $request->validate($rules);

        // $password = Hash::make($validatedData['Password']);

        $user->Password = $validatedData['password'];
        $user->password_reset_token = null;
        $user->exp_date = null;
        $user->update();

        return back()->with('success', 'password has been changed');
    }
}
