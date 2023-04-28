<!DOCTYPE html>
<html>
<head>
  <title>Sponsors</title>
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      border: 1px solid black;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: lightgray;
    }
  </style>
</head>
<body>
  <table>
    <tr>
      <th>CIF</th>
      <th>Nom</th>
      <th>Logo</th>
      <th>Adreça</th>
      <th>Curses</th>
      <th>Primera Plana</th>
    </tr>
    @foreach ($sponsors as $sponsor)
      <tr>
        <td>{{ $sponsor->CIF }}</td>
        <td>{{ $sponsor->nom }}</td>
        <td>{{ $sponsor->logo }}</td>
        <td>{{ $sponsor->adreca }}</td>
        <td>{{ implode(', ', json_decode($sponsor->curses)[1]), implode(', ', json_decode($sponsor->curses)[1]) }}</td>
        <td>{{ $sponsor->primera_plana ? 'Sí' : 'No' }}</td>
      </tr>
    @endforeach
  </table>
</body>
</html>