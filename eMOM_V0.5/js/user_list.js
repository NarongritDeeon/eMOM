document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search');
    const tableRows = document.querySelectorAll('tbody tr');
    const rowsPerPage = 10;
    let currentPage = 1;

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const rowsToShow = Array.from(tableRows).filter(row => {
            const cells = Array.from(row.getElementsByTagName('td'));
            const rowText = cells.map(cell => cell.textContent.toLowerCase()).join(' ');
            return rowText.includes(searchTerm);
        });
        paginate(rowsToShow);
    }

    function paginate(rowsToShow) {
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        tableRows.forEach((row, index) => {
            row.style.display = (rowsToShow.indexOf(row) >= start && rowsToShow.indexOf(row) < end) ? '' : 'none';
        });

        updatePaginationControls(rowsToShow.length);
    }

    function updatePaginationControls(totalRows) {
        const pageCount = Math.ceil(totalRows / rowsPerPage);
        const paginationContainer = document.getElementById('pagination');
        paginationContainer.innerHTML = '';

        // Previous button
        const prevButton = document.createElement('button');
        prevButton.textContent = '<';
        prevButton.classList.add('pagination-prev');
        prevButton.disabled = currentPage === 1;
        prevButton.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                filterTable();
            }
        });
        paginationContainer.appendChild(prevButton);

        // Page info
        const pageInfo = document.createElement('span');
        pageInfo.textContent = `หน้า ${currentPage}/${pageCount}`;
        pageInfo.classList.add('pagination-info');
        paginationContainer.appendChild(pageInfo);

        // Next button
        const nextButton = document.createElement('button');
        nextButton.textContent = '>';
        nextButton.classList.add('pagination-next');
        nextButton.disabled = currentPage === pageCount;
        nextButton.addEventListener('click', () => {
            if (currentPage < pageCount) {
                currentPage++;
                filterTable();
            }
        });
        paginationContainer.appendChild(nextButton);
    }

    searchInput.addEventListener('input', filterTable);

    // Initialize table
    filterTable();
});
