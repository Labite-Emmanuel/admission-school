<?php $page = 'items-ordering-validate'; ?>
@extends('layout.mainlayout')
@section('content')

<div class="page-wrapper" style="margin-top: 20px;">
    <div class="content content-two">
        <div class="" >
            <h1 style="font-size: 30px;">Admission List</p>
        </div><br>

        <form action="{{url('validate-ordering')}}" method="get">
        
            <table class="table datatable">
                <thead class="thead-light">
                    <tr>
                        <th>N°</th>
                        <th>Designation</th>
                        <th>Unit Price (FCFA)</th>
                        <th>Quantity</th>
                        <th>Concession (%)</th>
                        <th>Concession (FCFA)</th>
                        <th>Amount (FCFA)</th>
                        <th></th>
                </thead>

                <tbody>
                    <?php $i = 0; 
                    $grandTotal = 0;

                    ?>
                    @foreach($provins as $provin)
                    <?php $i++; 
                    // Calcul du montant total
                        $totalAmount = $provin->qtty * $provin->unit_price;
                        $Amountconcession = ($provin->concession / 100) * $provin->amount_proforma;
                        $grandTotal += $totalAmount;
                    ?>
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$provin->description}}
                            <input type="hidden" name="code_academic" value="{{$code_academic}}">
                            <input type="hidden" name="classroom" value="{{$classroom}}">
                            <input type="hidden" name="id_article[]" value="{{$provin->id_article}}">
                            <input type="hidden" name="description[]" value="{{$provin->description}}">
                        </td>
                        <td>{{$provin->unit_price}}
                            <input type="hidden" name="unit_price[]" value="{{$provin->unit_price}}">
                        </td>
                        <td>
                            @if($provin->modify_qty == "Yes")
                                <select name="qtty[]" class="form-select quantity-select" data-unit-price="{{ $provin->unit_price }}">
                                    <option >{{$provin->qtty}}</option>
                                    @for($j = 1; $j <= 100; $j++)
                                        <option value="{{$j}}">{{$j}}</option>
                                    @endfor
                                </select>
                            @else
                                {{$provin->qtty}}
                                <input type="hidden" name="qtty[]" value="{{$provin->qtty}}">
                            @endif
                        </td>
                        <td>{{ $provin->concession }}
                             <input type="hidden" name="concession[]" value="{{$provin->concession}}">
                        </td>
                        <td >{{$Amountconcession}}
                            <input type="hidden" name="amount_concession[]" value="{{$Amountconcession}}">
                        </td>
                        <td class="amount-cell" data-initial-amount="{{ $provin->amount_proforma }}">{{$provin->amount_proforma}}
                            <input type="hidden" name="amount[]" value="{{$totalAmount}}">
                        </td>
                        <td>
                            @if($provin->modify_size == "Yes")
                            <select name="size[]" id="" class="form-select">
                                <option >Choose Size...</option>
                                @foreach($sizes as $size)
                                    <option value="{{$size->code}}">{{$size->code}}</option>
                                @endforeach
                            </select>
                            @else
                                <input type="hidden" name="size[]" value="null">
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="font-weight: bold;">
                        <td colspan="6" style="text-align: right;">Total General:</td>
                        <td id="grand-total">{{ $grandTotal }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table><br><br>

            <div class="footer">
                <button type="submit" class="btn btn-warning" style="float: right;">Save</button>
            </div><br><br><br>

        </form>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Écouter les changements de quantité
        document.querySelectorAll('.quantity-select').forEach(function(select) {
            select.addEventListener('change', function() {
                const quantity = parseInt(this.value);
                const unitPrice = parseFloat(this.getAttribute('data-unit-price'));
                const amountCell = this.closest('tr').querySelector('.amount-cell');
                
                // Calculer le nouveau montant
                const newAmount = quantity * unitPrice;
                
                // Mettre à jour l'affichage
                amountCell.textContent = newAmount;
            });
        });
    });

</script>

@endsection