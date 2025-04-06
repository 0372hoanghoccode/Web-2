const books = document.querySelector(".book-section .books");
const searchInput = document.querySelector("#searchInput");
const searchInputIcon = document.querySelector(".searchInputContainer i");

// Khai báo các biến toàn cục để đồng bộ với product.js
let listCategoryIds = [];
let priceRange = null;
let keyword = null;

function handleSearchInputFocus(keyword) {
  $.ajax({
    type: "post",
    url: "controller/product.controller.php",
    dataType: "html",
    data: {
      isSearch: true,
      keyword,
      modelPath: "../model",
    },
  }).done(function (result) {
    const data = JSON.parse(result);
    document.querySelector(".notification-title").classList.remove("hide");
    if (data.success) {
      renderHTMLSearchResult(data);
    } else {
      const books = document.querySelector(".book-section .books");
      books.innerHTML = `
        <div class="no-search-result">
          <p>${data.message}</p>
        </div>
      `;
    }

    if (keyword == "") {
      document.querySelector(".notification").style.display = "none";
    } else {
      document.querySelector(".notification").style.display = "flex";
    }
  });
}

function renderHTMLSearchResult(data) {
  books.innerHTML = "";
  data.products.forEach((product) => {
    const html = `
      <div class="book">
        <a href="index.php?page=product_detail&pid=${product.id}">${product.name}</a>
      </div>
    `;
    books.insertAdjacentHTML("afterbegin", html);
  });
}

$(document).ready(function () {
  // Khôi phục từ localStorage khi tải trang
  if (localStorage.getItem("listCategoryIds")) {
    listCategoryIds = JSON.parse(localStorage.getItem("listCategoryIds"));
  }
  if (localStorage.getItem("priceRange")) {
    priceRange = localStorage.getItem("priceRange");
  }
  if (localStorage.getItem("keyword")) {
    keyword = localStorage.getItem("keyword");
    searchInput.value = keyword;
    searchInputIcon.classList.remove("hide");
  }

  $("#searchInput").on("input", function () {
    const keyword = $(this).val();
    if (keyword != "") {
      searchInputIcon.classList.remove("hide");
    } else {
      searchInputIcon.classList.add("hide");
    }
    handleSearchInputFocus(keyword);
  });

  $("#searchInput").on("focus", function () {
    const keyword = $(this).val();
    handleSearchInputFocus(keyword);
  });
});

document.querySelector("#searchButton").addEventListener("click", (e) => {
  const searchInput = document.querySelector("#searchInput");
  keyword = searchInput.value;
  if (keyword == "") {
    searchInput.focus();
    return;
  }

  var queryString = window.location.search;
  var params = new URLSearchParams(queryString);
  var currentPage = params.get("page");

  // Lưu tất cả bộ lọc vào localStorage
  localStorage.setItem("keyword", keyword);
  localStorage.setItem("listCategoryIds", JSON.stringify(listCategoryIds));
  localStorage.setItem("priceRange", priceRange);

  if (currentPage != "product") {
    window.location.href = "index.php?page=product";
  } else {
    location.reload();
  }
});

searchInputIcon.addEventListener("click", (e) => {
  const searchInput = document.querySelector("#searchInput");
  searchInput.value = "";
  localStorage.removeItem("keyword");
  localStorage.removeItem("listCategoryIds"); // Xóa bộ lọc khi xóa từ khóa
  localStorage.removeItem("priceRange");
  searchInputIcon.classList.add("hide");

  var queryString = window.location.search;
  var params = new URLSearchParams(queryString);
  var currentPage = params.get("page");

  if (currentPage == "product") {
    location.reload();
  }
});

if (localStorage.getItem("keyword")) {
  searchInputIcon.classList.remove("hide");
}

// Small device menu handling
const menuBar = document.querySelector(".headerRight--smallDevice i");
const headerRightOptions = document.querySelector(".headerRight--options");
const headerRightMenuBar = document.querySelector(".headerRightMenuBar");
const headerRightCloseBar = document.querySelector(".headerRightCloseBar");

menuBar.addEventListener("click", (e) => {
  headerRightOptions.classList.toggle("hide");
  headerRightMenuBar.classList.toggle("hide");
  headerRightCloseBar.classList.toggle("hide");
});

headerRightCloseBar.addEventListener("click", (e) => {
  headerRightOptions.classList.toggle("hide");
  headerRightMenuBar.classList.toggle("hide");
  headerRightCloseBar.classList.toggle("hide");
});

searchInput.addEventListener("keydown", (event) => {
  if (event.key === "Enter") {
    event.preventDefault();
    document.querySelector("#searchButton").click();
  }
});
