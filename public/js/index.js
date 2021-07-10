(function () {

// 変数
const $table = document.querySelector('.table');
const $trs = $table.querySelectorAll('tr');
const $tds = $table.querySelectorAll('td');

// 回して変数



// クリックイベント
$tds.forEach($td => {
    $td.addEventListener('click', () => {
        index1 = $td.parentNode.getAttribute('trIndex');
        index2 = $td.getAttribute('tdIndex');
        api(index1,index2);
    });
});


// 関数
const target = (i1,i2,user) => {
    let $T = $trs[i1].querySelectorAll('td')[i2];
    if(user == 0) {
        $T.textContent = '○';
        $T.classList.add('white');
    }
    if(user == 1) {
        $T.textContent = '●';
    }
}



// ajax通信関数
const api = (i1,i2) => {
    // console.log(`縦番号${i1}`);
    // console.log(`横番号${i2}`);
    fetch(`/ajax/send`, set(i1,i2))
    .then(response => {
        return response.json();
    })
    .then(json => {
        // console.log(json);
        // おけない場所を選択した場合
        // console.log(json['borad']);
        if(json['problem']) {
            return console.log('その置き場所は問題あり');
        }
        // テスト
        if(json['count']) {
            // console.log('隣にオセロはあります');
            console.log(json['count']);
        }
        if(json['count'] == 0) {
            console.log('カウントは0');
        }
        // target(json['i1'], json['i2'],json['user']);
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
            'i2' : i2
        })
    }
    return setting;
}
} ());