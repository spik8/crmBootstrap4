<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="1" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="6" face="Calibri">Tygodniowy Raport Rozmów Rekrutacyjnych</font></td>
        <td colspan="5" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
            <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
    </tr>
    <tr>
        <td colspan="2" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">Raport od {{$date_start}} do {{$date_stop}}</td>
        <td style="border:1px solid #231f20;padding:3px;background:#50504f;color:#efd88f;font-size:1.3em;" colspan="3">Zakończone negatywnie</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;color:#efd88f;font-size:1.3em;" colspan="1"></td>
    </tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Oddział</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Liczba przeprowadzonych rekrutacji</th>
        <th style="border:1px solid #231f20;padding:3px;background:#50504f">Inna Oferta</th>
        <th style="border:1px solid #231f20;padding:3px;background:#50504f">Nie zainteresowany</th>
        <th style="border:1px solid #231f20;padding:3px;background:#50504f">Inne</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Zakończone Sukcesem</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as  $item)
        <tr>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->dep_name.' '.$item->dep_name_type}}</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->counted}}</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->other_offer}}</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->not_interested}}</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->other}}</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->finished_positive}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
