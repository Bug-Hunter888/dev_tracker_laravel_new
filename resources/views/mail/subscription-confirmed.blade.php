<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DevTracker — Subscription Confirmed</title>
</head>
<body style="margin:0;padding:0;background:#0a0a0a;font-family:'Courier New',Courier,monospace;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#0a0a0a;padding:40px 20px;">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

        {{-- Header --}}
        <tr>
          <td style="background:#000;border:1px solid #1e1e1e;border-bottom:3px solid #39FF14;padding:28px 32px;">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td>
                  <span style="color:#39FF14;font-size:18px;font-weight:bold;letter-spacing:-0.5px;">./DevTracker_</span>
                </td>
                <td align="right">
                  <span style="display:inline-block;background:rgba(57,255,20,0.08);border:1px solid rgba(57,255,20,0.2);color:#39FF14;font-size:11px;padding:4px 10px;letter-spacing:2px;text-transform:uppercase;">
                    {{ strtoupper($plan) }} PLAN
                  </span>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- Body --}}
        <tr>
          <td style="background:#0d0d0d;border:1px solid #1e1e1e;border-top:none;padding:36px 32px;">

            <p style="color:#2a2a2a;font-size:11px;letter-spacing:3px;text-transform:uppercase;margin:0 0 8px 0;">// TRANSACTION_COMPLETE</p>
            <h1 style="color:#ffffff;font-size:26px;font-weight:bold;margin:0 0 8px 0;line-height:1.2;">
              Welcome to <span style="color:#39FF14;">{{ strtoupper($plan) }}</span>, {{ $user->name }}.
            </h1>
            <p style="color:#555;font-size:14px;margin:0 0 32px 0;line-height:1.6;">
              Your subscription is now active. All {{ strtoupper($plan) }} features are unlocked for <strong style="color:#888;">{{ $teamName }}</strong>.
            </p>

            {{-- Receipt box --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#111;border:1px solid #1e1e1e;margin-bottom:28px;">
              <tr>
                <td style="padding:16px 20px;border-bottom:1px solid #1e1e1e;">
                  <span style="color:#2a2a2a;font-size:10px;letter-spacing:3px;text-transform:uppercase;">// RECEIPT</span>
                </td>
              </tr>
              <tr>
                <td style="padding:0 20px;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:10px 0;border-bottom:1px solid #161616;color:#555;font-size:12px;">Plan</td>
                      <td align="right" style="padding:10px 0;border-bottom:1px solid #161616;color:#fff;font-size:12px;font-weight:bold;text-transform:uppercase;">{{ $plan }}</td>
                    </tr>
                    <tr>
                      <td style="padding:10px 0;border-bottom:1px solid #161616;color:#555;font-size:12px;">Amount</td>
                      <td align="right" style="padding:10px 0;border-bottom:1px solid #161616;color:#fff;font-size:12px;">{{ $amount }} / month</td>
                    </tr>
                    <tr>
                      <td style="padding:10px 0;border-bottom:1px solid #161616;color:#555;font-size:12px;">Team</td>
                      <td align="right" style="padding:10px 0;border-bottom:1px solid #161616;color:#fff;font-size:12px;">{{ $teamName }}</td>
                    </tr>
                    <tr>
                      <td style="padding:10px 0;border-bottom:1px solid #161616;color:#555;font-size:12px;">Date</td>
                      <td align="right" style="padding:10px 0;border-bottom:1px solid #161616;color:#fff;font-size:12px;">{{ now()->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                      <td style="padding:10px 0;border-bottom:1px solid #161616;color:#555;font-size:12px;">Status</td>
                      <td align="right" style="padding:10px 0;border-bottom:1px solid #161616;color:#39FF14;font-size:12px;font-weight:bold;">PAID</td>
                    </tr>
                    <tr>
                      <td style="padding:10px 0;color:#555;font-size:12px;">Transaction ref</td>
                      <td align="right" style="padding:10px 0;color:#888;font-size:12px;">{{ $transactionRef }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            {{-- What's unlocked --}}
            <p style="color:#2a2a2a;font-size:10px;letter-spacing:3px;text-transform:uppercase;margin:0 0 12px 0;">// FEATURES_UNLOCKED</p>
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:32px;">
              @if($plan === 'pro')
              @foreach(['Unlimited projects', 'Full analytics dashboard', 'Workflow automations', 'Up to 10 team members', 'Priority support'] as $feature)
              <tr>
                <td style="padding:5px 0;color:#555;font-size:13px;">
                  <span style="color:#39FF14;margin-right:8px;">+</span>{{ $feature }}
                </td>
              </tr>
              @endforeach
              @else
              @foreach(['Everything in PRO', 'Unlimited team members', 'Advanced role permissions', 'Unlimited file storage', 'Dedicated support channel'] as $feature)
              <tr>
                <td style="padding:5px 0;color:#555;font-size:13px;">
                  <span style="color:#eab308;margin-right:8px;">+</span>{{ $feature }}
                </td>
              </tr>
              @endforeach
              @endif
            </table>

            {{-- CTA button --}}
            <table cellpadding="0" cellspacing="0" style="margin-bottom:32px;">
              <tr>
                <td style="background:#39FF14;padding:14px 28px;">
                  <a href="{{ url('/dashboard') }}" style="color:#000;font-size:13px;font-weight:bold;text-decoration:none;text-transform:uppercase;letter-spacing:1px;">
                    GO TO DASHBOARD →
                  </a>
                </td>
              </tr>
            </table>

            <p style="color:#333;font-size:12px;line-height:1.6;margin:0;">
              This is a demo subscription — no real charge was made.<br>
              Questions? Reply to this email or visit your team settings.
            </p>

          </td>
        </tr>

        {{-- Footer --}}
        <tr>
          <td style="padding:20px 32px;text-align:center;">
            <p style="color:#222;font-size:11px;margin:0;">
              &copy; {{ date('Y') }} DevTracker &bull; Built on Laravel + Tailwind CSS
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>
