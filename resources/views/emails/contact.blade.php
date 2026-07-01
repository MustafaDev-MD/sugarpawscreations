<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body style="margin:0;padding:30px;font-family:Arial,Helvetica,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">

                <table width="650" cellpadding="0" cellspacing="0"
                    style="background:#161921;border:1px solid #2a3140;border-radius:10px;overflow:hidden;">

                    <!-- Header -->
                    <tr>
                        <!-- 📩 -->
                        <td style="padding:30px;background:#1d232e;text-align:center;">
                            <h1 style="margin:0;color:#ffffff;font-size:26px;">
                                New Commission Request
                            </h1>

                            <p style="margin-top:8px;color:#9ca3af;font-size:14px;">
                                Someone has submitted your commission form.
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:35px;">

                            <table width="100%" cellpadding="0" cellspacing="0">

                                <tr>
                                    <td style="padding:12px 0;border-bottom:1px solid #2a3140;">
                                        <strong style="color:#ffffff;">Full Name</strong><br>
                                        <span style="color:#c5c8cf;">
                                            {{ $data['full_name'] }}
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:12px 0;border-bottom:1px solid #2a3140;">
                                        <strong style="color:#ffffff;">Email</strong><br>

                                        <a href="mailto:{{ $data['email'] }}"
                                            style="color:#7dd3fc;text-decoration:none;">
                                            {{ $data['email'] }}
                                        </a>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:12px 0;border-bottom:1px solid #2a3140;">
                                        <strong style="color:#ffffff;">Discord Username</strong><br>

                                        <span style="color:#c5c8cf;">
                                            {{ $data['discord_username'] }}
                                        </span>
                                    </td>
                                </tr>

                                @if(!empty($data['social_link']))
                                <tr>
                                    <td style="padding:12px 0;border-bottom:1px solid #2a3140;">
                                        <strong style="color:#ffffff;">
                                            Other Social Link
                                        </strong><br>

                                        <a href="{{ $data['social_link'] }}"
                                            style="color:#7dd3fc;text-decoration:none;">
                                            {{ $data['social_link'] }}
                                        </a>
                                    </td>
                                </tr>
                                @endif

                                @if(!empty($data['commission_details']))
                                <tr>
                                    <td style="padding:20px 0;">
                                        <strong style="color:#ffffff;">
                                            Commission Detail
                                        </strong>

                                        <div
                                            style="margin-top:12px;padding:18px;background:#1d232e;border-left:4px solid #5b8cff;color:#d1d5db;line-height:1.8;border-radius:6px;">

                                            {!! nl2br(e($data['commission_details'])) !!}

                                        </div>
                                    </td>
                                </tr>
                                @endif

                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:20px;text-align:center;background:#1d232e;">

                            <p style="margin:0;color:#8b93a5;font-size:13px;">
                                This email was automatically generated from your
                                <a href="{{ route('home') }}">
                                    <strong style="color:#ffffff;">
                                        {{ config('app.name') }}
                                    </strong>
                                </a>
                                commission form.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>