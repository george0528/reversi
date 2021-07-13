(function () {

// 変数
const $table = document.querySelector('.table');
const $trs = $table.querySelectorAll('tr');
const $tds = $table.querySelectorAll('td');
const $color = document.querySelector('.color');

// 回して変数



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
        // おけない場所を選択した場合
        if(json['problem']) {
            return console.log('その置き場所は置けません');
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
        // 置ける場所がない時　パス
        if(json['pass']) {
            console.log('置ける場所がありません。');
        } else {
            // 次に置ける場所を指定する
            nexts(json['nextCoords']);
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
        })
    }
    return setting;
}
} ());