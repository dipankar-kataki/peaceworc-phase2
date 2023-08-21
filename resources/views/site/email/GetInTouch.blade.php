<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mail</title>
    <style>
        table {
          font-family: arial, sans-serif;
          border-collapse: collapse;
          width: 100%;
        }
        
        td, th {
          border: 1px solid #dddddd;
          text-align: left;
          padding: 8px;
        }
        
        tr:nth-child(even) {
          background-color: #dddddd;
        }
    </style>
</head>
<body>
    <h5>New Enquiry For Peaceworc</h5>

    <table>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Subject</th>
          <th>Message</th>
        </tr>
        <tr>
            <td><p>{{ $details['name'] }}</p></td>
            <td><p>{{ $details['email'] }}</p></td>
            <td><p>{{ $details['subject'] }}</p></td>
            <td><p>{{ $details['message'] }}</p></td>
        </tr>
    </table>
</body>
</html>