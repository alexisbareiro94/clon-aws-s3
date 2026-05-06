const searchInput = document.getElementById('search-objects');

if (searchInput) {
    let timer;
    searchInput.addEventListener('input', () => {
        const bucketSlug = searchInput.dataset.bucketSlug;
        console.log(bucketSlug);

        clearTimeout(timer);
        timer = setTimeout(async () => {
            const q = searchInput.value;
            try {
                const res = await axios.get(`/${bucketSlug}?q=${q}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        // 'Accept': 'application/json'
                    }
                });


                const data = res.data;
                document.getElementById('file-list-body').innerHTML = data.html;
            } catch (error) {
                console.error(error);
            }
        }, 500);
    });
}
