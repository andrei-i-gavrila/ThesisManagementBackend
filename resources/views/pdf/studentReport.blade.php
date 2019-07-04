<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style>
        html {
            font-size: 0.70em;
        }

        table {
            border: 1px solid;
            border-collapse: collapse;
        }

        td {
            border: 1px solid;
            border-collapse: collapse;
        }

    </style>
</head>
<body>
Universitatea Babeș-Bolyai Cluj-Napoca<br>
Facultatea de Matematică și Informatică<br>
Anexa 3/II
<br>
<p style="text-align: center; text-decoration: underline">
    Borderou de evaluare Comisie
    <br>
    <b>Proba 2: Prezentarea şi susţinerea lucrării de licenţă/proiect de diplomă</b>({{ now()->year }})
</p>
<p><b>Student</b>(nume, prenume) {{ $paper->student->name }}</p>
<p><b>Specializarea:</b> {{ $paper->examSession->department }}</p>

<hr>
<br><br>
<b>Comisia de evaluare şi notarea a lucrării de licenţă:</b>
<br><br>
<table style="width: 80%; margin: auto;">
    <tr>
        <td></td>
        <td>Nota propusă</td>
        <td>Semnătura</td>
    </tr>
    <tr>
        <td style="text-align: right">Presedinte (P)</td>
        <td style="text-align: center">{{ $paper->keyedAverages['leader_id'] }}</td>
        <td></td>
    </tr>
    <tr>
        <td style="text-align: right">Membru (1)</td>
        <td style="text-align: center">{{ $paper->keyedAverages['member1_id'] }}</td>
        <td></td>
    </tr>
    <tr>
        <td style="text-align: right">Membru (2)</td>
        <td style="text-align: center">{{ $paper->keyedAverages['member2_id'] }}</td>
        <td></td>
    </tr>
</table>
<br><br>
<b>Nota finală proba 2:</b> {{ number_format(collect($paper->keyedAverages)->average(), 2, ',', '.') }}
<br>
(media aritmetică a notelor de mai sus)
<br><br><br>
<br><br><br>
<div style="text-align: right"><b>întocmit,</b></div>
<div><b>Data:</b></div>
<div style="text-align: right"><b>Secretar Comisie Examen de Licenţă</b></div>
<div style="text-align: right">{{ Auth::user()->name }}</div>
<div style="text-align: right"><b>Semnătura</b></div>

</body>
</html>



