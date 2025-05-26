// script.js 

 

 

document.addEventListener('DOMContentLoaded', function() { 

    const modal = document.getElementById('modal'); 

    const closeBtn = document.getElementById('closeBtn'); 

    const eventForm = document.getElementById('eventForm'); 

    const eventId = document.getElementById('eventId'); 

    const eventDate = document.getElementById('eventDate'); 

    const eventTitle = document.getElementById('eventTitle'); 

    const eventCategory = document.getElementById('eventCategory'); 

    const deleteBtn = document.getElementById('deleteBtn'); 

 

    // 日にちクリック（追加用） 

    document.querySelectorAll('.calendar td[data-date]').forEach(td => { 

        td.addEventListener('click', function(e) { 

            if (e.target.tagName === 'BUTTON') return; // 編集ボタンは無視 

            openModal({ 

                id: '', 

                event_date: this.dataset.date, 

                title: '', 

                category: '1' 

            }); 

        }); 

    }); 

 

    // 編集ボタンクリック 

    document.querySelectorAll('.edit-btn').forEach(btn => { 

        btn.addEventListener('click', function(e) { 

            e.stopPropagation(); 

            openModal({ 

                id: this.dataset.id, 

                event_date: this.dataset.date, 

                title: this.dataset.title, 

                category: this.dataset.category 

            }); 

        }); 

    }); 

 

    // モーダル開く 

    function openModal(data) { 

        eventId.value = data.id; 

        eventDate.value = data.event_date; 

        eventTitle.value = data.title; 

        eventCategory.value = data.category; 

 

        deleteBtn.style.display = data.id ? 'inline' : 'none'; 

        modal.style.display = 'block'; 

    } 

 

    // モーダル閉じる 

    closeBtn.addEventListener('click', function() { 

        modal.style.display = 'none'; 

    }); 

 

    // 外側クリックで閉じる 

    window.addEventListener('click', function(e) { 

        if (e.target === modal) { 

            modal.style.display = 'none'; 

        } 

    }); 

 

    // 【ここが追加！】削除ボタン押したとき 

    deleteBtn.addEventListener('click', function() { 

        if (!eventId.value) { 

            alert('IDがありません'); 

            return; 

        } 

        if (confirm('本当に削除しますか？')) { 

            // ここで delete.php に飛ばす 

            location.href = 'delete.php?id=' + eventId.value; 

        } 

    }); 

}); 