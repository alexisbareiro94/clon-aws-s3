const searchInput = document.getElementById('search-buckets');

if (searchInput) {
    let timer;
    searchInput.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(async () => {
            const q = searchInput.value;
            console.log(q)
            try {
                const res = await axios.get(`/buckets?q=${q}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                const data = res.data;
                document.getElementById('buckets-table-body').innerHTML = data.html;
            } catch (error) {
                console.error(error);
            }
        }, 500);
    });
}
