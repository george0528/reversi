(function () {

// 変数
const $table = document.querySelector('.table');
const $trs = $table.querySelectorAll('tr');
const $tds = $table.querySelectorAll('td');
const $color = document.querySelector('.color');
const player1_count = document.querySelector('.player1_count');
const player2_count = document.querySelector('.player2_count');
const room = document.querySelector('#room_id').value;
const finish = document.querySelector('.finish');
const winner = finish.querySelector('.winner');
const deleteBtn = document.querySelector('#delete');

// 回して変数

// 終了ボタン　クリックイベント
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
    ];
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
        api(index1,index2);
    });
});


// 関数
// 指定の場所に置く　変更する
const target = (i1,i2,user) => {
    let $T = $trs[i1].querySelectorAll('td')[i2];
    if(user == 2) {
        if ($T.classList.contains('black')) {
            $T.classList.remove('black')
        }
        $T.classList.add('white');
    }
    if(user == 1) {
        if ($T.classList.contains('white')) {
            $T.classList.remove('white');
        }
        $T.classList.add('black');
    }
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

// ajax通信関数
const api = (i1,i2) => {
    fetch(`/ajax/send`, set(i1,i2))
    .then(response => {
        return response.json();
    })
    .then(json => {
        console.log(json);
        // おけない場所を選択した場合
        if(json['problem']) {
            return console.log('その置き場所は置けません');
        }
        if(pass) {
            pass.classList.remove('open');
        }
        // 指定の場所に置く
        target(json['i1'], json['i2'],json['user']);
        // 変更する　リバース
        reverse(json['changes'],json['user']);
        //色を変更する
        if($color.dataset.color == 1) {
            $color.dataset.color = 2;
            $color.textContent = '白';
            $color.classList.add('test-white');
        } else {
            $color.dataset.color = 1;
            $color.textContent = '黒';
            $color.classList.remove('test-white');
        }
        // nextクラスをとる
        document.querySelectorAll('.next').forEach(e => {
            e.classList.remove('next');
        });
        // コマの数を更新
        if(json['counts']) {
            player1_count.textContent = String(json['counts'][1]);
            player2_count.textContent = String(json['counts'][2]);
        }
        // 終了か
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
        // 置ける場所がない時　パス
        if(json['pass']) {
            console.log('置ける場所がありません。');
            var pass = document.querySelector('.pass');
            console.log(pass);
            pass.classList.add('open');
        } else {
            // 次に置ける場所を指定する
            nexts(json['nextCoords']);
            $nexts = document.querySelectorAll('.next');
            $nexts[0].click();
        }
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
            'color' : $color.dataset.color,
            'room' : room,
        }),
    }
    return setting;
}
} ());