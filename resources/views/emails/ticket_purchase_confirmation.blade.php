<!DOCTYPE html>
<html>
<head>
    <title>Ticket Purchase Confirmation</title>
</head>
<body>
    <h1>Thank you for your purchase, {{ $ticketSale->user->name }}!</h1>

    <p>We are excited to confirm your ticket purchase for the event:</p>
    <p><strong>{{ $ticketSale->ticketType->event->title }}</strong></p>
    <p>Ticket Type: {{ $ticketSale->ticketType->name }}</p>
    <p>Quantity: {{ $ticketSale->quantity }}</p>
    <p>Total Price: ${{ number_format($ticketSale->total_price, 2) }}</p>
    <p>We look forward to seeing you at the event!</p>

    <p>If you have any questions, feel free to contact us.</p>

    <p>Best regards,</p>
    <p>The Dev Team</p>
</body>
</html>
