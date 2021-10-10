(function () {

    // 変数
    const $table = document.querySelector('.table');
    const $trs = $table.querySelectorAll('tr');
    const $tds = $table.querySelectorAll('td');
    const $color = document.querySelector('.color');
    const pass = document.querySelector('.pass').querySelector('button');
    const finish = document.querySelector('.finish');
    const winner = finish.querySelector('.winner');
    const room = document.querySelector('#room_id').value;
    const color = 1;
    const player1_count = document.querySelector('.player1_count');
    const player2_count = document.querySelector('.player2_count');
    const deleteBtn = document.querySelector('#delete');
    // テスト

    // 終了ボタン クリックイベント
    deleteBtn.addEventListener('click', () => {
        finish.classList.remove('open');
        var url = `${location.protocol}//${location.host}`;
        window.location.href = url;
    })
    // ロード後イベント
    window.onload = () => {
        $nexts = [
            [4,2],
            [5,3],
            [2,4],
            [3,5]
        ]
        nexts($nexts);
    }
    // クリックイベント
    $tds.forEach($td => {
        $td.addEventListener('click', () => {
            let index1 = $td.parentNode.dataset.trIndex;
            let index2 = $td.dataset.tdIndex;
            // nextを持っていたら
            if($td.classList.contains('next')) {
            }
            api(index1,index2,false);
        });
    });
    // 
    pass.addEventListener('click', () => {
        api(1,1,true);
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
    const api = (i1,i2,pass) => {
        fetch(`/ajax/send`, set(i1,i2,pass))
        .then(response => {
            return response.json();
        })
        .then(json => {
            // おけない場所を選択した場合
            if(json['problem']) {
                return console.log('その置き場所は置けません');
            }
            // パスでは無い時
            if(json['i1'] && json['i2']) {
                // 指定の場所に置く
                target(json['i1'], json['i2'],json['user']);
                put([json['i1'],json['i2']]);
                // 変更する　リバース
                reverse(json['changes'],json['user']);
            }
            // 前のnextクラスを取る
            document.querySelectorAll('.next').forEach(e => {
                e.classList.remove('next');
            });
            // コマの数を更新
            if(json['counts']) {
                player1_count.textContent = String(json['counts'][1]);
                player2_count.textContent = String(json['counts'][2]);
            }
            // ボットが置く
            setTimeout(() => {
                if(json['botChanges']) {
                    target(json['botCoord'][0], json['botCoord'][1], changeColor(json['user']));
                    put(json['botCoord']);
                    reverse(json['botChanges'], changeColor(json['user']));
                }
                // 置ける場所がない時　パス
                if(json['pass']) {
                    document.querySelector('.pass').classList.add('open');
                } else {
                    // 次に置ける場所を指定する
                    nexts(json['nextCoords']);
                }
                // 終了していたら
                if(json['finish']) {
                    finish.classList.add('open');
                    if(json['winner'] == 1) {
                        winner.textContent = '黒';
                    } else if(json['winner'] == 2) {
                        winner.textContent = '白';
                    } else {
                        winner.textContent = '引き分け';
                    }
                }
                // テスト
                $nexts = document.querySelectorAll('.next');
                if(!json['pass'] && $nexts) {
                    var $next = $nexts[random($nexts)];
                    $next.click();
                } else {
                    // document.querySelector('.pass').querySelector('button').click();
                }
            }, 500);
        })
        .catch(error => {
            console.log('エラー：' + error);

        });
    }
    // api設定
    const set = (i1,i2,pass) =>{
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
    const random = ($nexts) => {
        let min = 0;
        let max = $nexts.length;
        max--;
        return Math.floor( Math.random() * (max + 1 - min) ) + min ;
    }
    } ());