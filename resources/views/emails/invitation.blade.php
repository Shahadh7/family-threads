<!DOCTYPE html>
<html>
<head>
    <title>Invitation to Join Family Threads</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
        }
        .email-header h1 {
            font-size: 24px;
            margin: 0;
            color: #333333;
        }
        .email-body {
            padding: 20px;
            text-align: center;
        }
        .email-body p {
            font-size: 16px;
            color: #555555;
            line-height: 1.5;
        }
        .email-body h2 {
            font-size: 20px;
            color: #333333;
            margin: 20px 0;
        }
        .email-footer {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #777777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>You're Invited to Join Family Threads!</h1>
        </div>
        <div class="email-body">
            <p>To join your family, use the following family code when registering:</p>
            <h2>{{ $familyCode->family_code }}</h2>
            <p>We look forward to having you in the Family Threads application!</p>
        </div>
        <div class="email-footer">
            <p>&copy; 2024 Family Threads. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
