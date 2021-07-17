(function () {

    // 変数
    const $table = document.querySelector('.table');
    const $trs = $table.querySelectorAll('tr');
    const $tds = $table.querySelectorAll('td');
    const $color = document.querySelector('.color');
    const room = 1;
    const color = 1;
    let count = 0;
    var pass = false;
    // クリックイベント
    $tds.forEach($td => {
        $td.addEventListener('click', () => {
            let index1 = $td.parentNode.dataset.trIndex;
            let index2 = $td.dataset.tdIndex;
            // nextを持っていたら
            if($td.classList.contains('next')) {
            }
            api(index1,index2);
        });
    });
    
    
    // 関数
    // 指定の場所に置く　変更する
    const target = (i1,i2,user) => {
        let $T = $trs[i1].querySelectorAll('td')[i2];

        if(user == 2) {
            $T.textContent = '○';
            $T.classList.add('white');
        }
        if(user == 1) {
            $T.textContent = '●';
            // whiteクラスを持っている時
            if($T.classList.contains('white')) {
                $T.classList.remove('white');
            }
        }
    }
    // 置かれた場所にクラスを付与
    const put = (coord) => {
        let $T = $trs[coord[0]].querySelectorAll('td')[coord[1]];
        // 置かれた場所にクラスを付与
        $put = document.querySelectorAll('.put');
        if($put) {
            $put.forEach($p => {
                $p.classList.remove('put');
            });
        }
        $T.classList.add('put');
    }
    // リバースする
    const reverse = ($changes,user) => {
        $changes.forEach(elem => {
            target(elem[0],elem[1],user);
        });
    }
    // 次に置ける場所を指定する
    const nexts = (nexts) => {
        nexts.forEach(elem => {
            let $T = $trs[elem[0]].querySelectorAll('td')[elem[1]];
            $T.classList.add('next');
        });
    }
    const changeColor = (color) => {
        if(color == 1) {
            return 2;
        }
        if(color == 2) {
            return 1;
        }
    }
    
    // ajax通信関数
    const api = (i1,i2) => {
        fetch(`/ajax/send`, set(i1,i2))
        .then(response => {
            return response.json();
        })
        .then(json => {
            var pass = false;
            console.log(json);
            // おけない場所を選択した場合
            if(json['problem']) {
                return console.log('その置き場所は置けません');
            }
            // 指定の場所に置く
            target(json['i1'], json['i2'],json['user']);
            put([json['i1'],json['i2']]);
            // 変更する　リバース
            reverse(json['changes'],json['user']);
            // 前のnextクラスを取る
            document.querySelectorAll('.next').forEach(e => {
                e.classList.remove('next');
            });
            if(json['finish']) {
                return console.log('ゲームが終了しました');
            }
            // ボットが置く
            setTimeout(() => {
                if(json['botChanges']) {
                    target(json['botCoord'][0], json['botCoord'][1], changeColor(json['user']));
                    put(json['botCoord']);
                    reverse(json['botChanges'], changeColor(json['user']));
                } else {
                    console.log('ボットが置ける所がありません');
                }
                // 置ける場所がない時　パス
                if(json['pass']) {
                    pass = true;
                    console.log('置ける場所がありません。');
                } else {
                    // 次に置ける場所を指定する
                    nexts(json['nextCoords']);
                }
                // テスト
                $next = document.querySelector('.next');
                count++;
                if(count != 10 && $next) {
                    $next.click();
                }
            }, 0);
        })
        .catch(error => {
            console.log('エラー：'+error);
        });
    }
    
    // api設定
    const set = (i1,i2) =>{
        let $token = document.querySelector('meta[name="csrf-token"]').content;
        let setting = {
            headers: {
                "X-CSRF-TOKEN" : $token,
                "Content-Type" : 'application/json'
            },
            method: "post",
            body : JSON.stringify({
                'i1' : i1,
                'i2' : i2,
                'color' : color,
                'room' : room,
                'pass' : pass,
            })
        }
        return setting;
    }
    } ());