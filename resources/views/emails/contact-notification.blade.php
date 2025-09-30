<!DOCTYPE html>
<html>
<head>
    <title>New Contact Message</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #4CAF50; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f9f9f9; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #333; }
        .value { margin-top: 5px; padding: 10px; background-color: white; border-left: 3px solid #4CAF50; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Contact Message</h1>
            <p>Cinema Paradiso Website</p>
        </div>
        
        <div class="content">
            <div class="field">
                <div class="label">Name:</div>
                <div class="value">{{ $contact->name }}</div>
            </div>
            
            <div class="field">
                <div class="label">Email:</div>
                <div class="value">{{ $contact->email }}</div>
            </div>
            
            <div class="field">
                <div class="label">Message:</div>
                <div class="value">{{ $contact->message }}</div>
            </div>
            
            <div class="field">
                <div class="label">Submitted At:</div>
                <div class="value">{{ $contact->created_at->format('F j, Y at g:i A') }}</div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 20px; color: #666;">
            <p>This message was sent from the Cinema Paradiso contact form.</p>
        </div>
    </div>
</body>
</html>