@extends('layouts.empty')
@section('title','Калькулятор криптоинвЭстора')
@section('content')
    <div class="uk-section">
        <div class="uk-container">
            <h1>
                Калькулятор инв<span>э</span>стора
                <img src="{{ asset('images/smile.svg') }}" alt="smile">
            </h1>
            <div class="uk-text-lead uk-text-center">А какова ваша вероятность попасть в аллокацию на coinlist.co?</div>

            <div class="code-wrapper uk-text-muted">
                <div class="code-wrapper__header">
                    <div class="code-wrapper__header-tab"><img src="{{ asset('images/bitcoin-logo.svg') }}" alt="bitcoin logo"> everyone_can.btc</div>
                </div>
                <div class="code-wrapper__body">
                    <div class="code-line font-text">
                        <div class="code-line__number" style="font-family: Cascadia Code;">1</div>
                        // введите данные в зеленые скобочки
                    </div>
                    <div class="code-line">
                        <div class="code-line__number">2</div>
                        <span class="purple">func </span> <span class="yellow">Calculator () { </span>
                    </div>
                    <div class="code-line">
                        <div class="code-line__number code-tab">3</div>
                        <span class="purple">var </span>
                        <span class="font-text">кол-во ваших аккаунтов =</span>
                        <span class="green" style="margin-left: 8px;">(</span>
                        <input type="text" name="accounts" class="uk-input uk-form-blank" placeholder="10" maxlength="3">
                        <span class="green" style="margin-right: 2px;">)</span>
                    </div>
                    <div class="code-line">
                        <div class="code-line__number code-tab">4</div>
                        <span class="purple">var </span>
                        <span class="font-text">кол-во мест =</span>
                        <span class="green" style="margin-left: 8px;">(</span>

                        <input type="text" name="winners" class="uk-input uk-form-blank medium" placeholder="30 000" maxlength="7">
                        <span class="green" style="margin-right: 2px;">)</span> *
                    </div>
                    <div class="code-line">
                        <div class="code-line__number code-tab">5</div>
                        <span class="purple">var </span>
                        <span class="font-text">кол-во участников =</span>
                        <span class="green" style="margin-left: 8px;">(</span>
                        <input type="text" name="participants" class="uk-input uk-form-blank big" placeholder="400 000" maxlength="8">
                        <span class="green" style="margin-right: 2px;">)</span> **
                    </div>
                    <div class="code-line" style="align-items: flex-start;">
                        <div class="code-line__number code-tab">6</div>
                        <div class="result-block">
                            <div class="result-block__first" style="margin-right: 8px;">
                                <span class="purple" style="margin: 0;">result </span>
                                 <span class="font-text" style="margin-left: 5px;"> = </span>
                            </div>
                            <div class="result-block__second">
                                <span id="response" class="green"></span>
                            </div>
                        </div>
                    </div>
                    <div class="code-line">
                        <div class="code-line__number">7</div>
                        }
                    </div>
                </div>
            </div>

            <div class="uk-text-center uk-margin-medium-bottom">
                <div id="error"></div>
                <div class="uk-button uk-button-primary postbutton">Посчитать</div>
            </div>

            <div class="sub-info">
                <b>Пример:</b><br>
                * Токенсейл Qredo 1 опция: выигрывает ~ 30 000 мест, 2 опция: выигрывает ~ 2500 мест<br>
                ** В токенсейлах на coinlist.co всегда участвует примерно ~ 350 000 - 400 000 человек
            </div>

        </div>
    </div>

    <footer>
        <div class="uk-container uk-text-center">
            <div class="uk-flex uk-flex-wrap uk-flex-between@m uk-flex-center uk-flex-middle">
                <a href="https://minus-30.ru" class="uk-padding-small" target="_blank"><img src="{{ asset('images/logo.svg') }}" alt="logo"></a>
                <div class="uk-text-small uk-text-muted uk-padding-small">&#169; От души для русскоязычного крипто-коммьюнити</div>
            </div>
        </div>
    </footer>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            $('input[name="accounts"]').focus();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('body').on('click', '.postbutton', function () {
                $("#response").html("");

                if($("input[name='accounts']").val().length < 1) {
                    $('input[name="accounts"]').focus();
                    $("#error").text("Введите количество аккаунтов!");
                    return;
                } else {
                    if(!$.isNumeric( $("input[name='accounts']").val() )) {
                        $('input[name="accounts"]').focus();
                        $("#error").text("Введите числа в поле кол-во аккаунтов!");
                        return;
                    }
                }

                if($("input[name='winners']").val().length < 1) {
                    $('input[name="winners"]').focus();
                    $("#error").text("Введите количество призовых мест!");
                    return;
                } else {
                    if(!$.isNumeric( $("input[name='winners']").val() )) {
                        $('input[name="winners"]').focus();
                        $("#error").text("Введите числа в поле призовых мест!");
                        return;
                    }
                }
                if($("input[name='participants']").val().length < 1) {
                    $('input[name="participants"]').focus();
                    $("#error").text("Введите примерное количество участников!");
                    return;
                } else {
                    if(!$.isNumeric( $("input[name='participants']").val() )) {
                        $('input[name="participants"]').focus();
                        $("#error").text("Введите числа в поле участников!");
                        return;
                    }
                }

                if(parseInt($("input[name='winners']").val(), 10) > parseInt($("input[name='participants']").val(), 10)) {
                    $("#error").text("Победителей больше чем участников может быть только в случае отключения всей России от интернета. Подумай еще раз.");
                    return;
                }

                $("#error").text("");


                $accounts = $("input[name='accounts']").val();
                $winners = $("input[name='winners']").val();
                $participants = $("input[name='participants']").val();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('postajax') }}',
                    data: {'accounts': $accounts, 'winners': $winners, 'participants': $participants},
                    success: function (data) {
                        $("#response").html(data.accounts);
                    }
                });

            });
        });
    </script>




@endsection
