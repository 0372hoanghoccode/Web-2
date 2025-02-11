const books = document.querySelector(".book-section .books");

function hanelSearchInputFocus(keyword) {
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

    // Nếu keyword == '' thì hide cái notification
    if (keyword == "") {
      document.querySelector(".notification").style.display = "none";
    } else {
      document.querySelector(".notification").style.display = "flex";
    }
  });
}

// Xử lý thay đổi input/focus search
const searchInputIcon = document.querySelector(".searchInputContainer i");
$(document).ready(function () {
  $("#searchInput").on("input", function () {
    const keyword = $(this).val();
    if (keyword != "") {
      searchInputIcon.classList.remove("hide");
    } else {
      searchInputIcon.classList.add("hide");
    }
    hanelSearchInputFocus(keyword);
  });
});

$(document).ready(function () {
  $("#searchInput").on("focus", function () {
    const keyword = $(this).val();
    hanelSearchInputFocus(keyword);
  });
});

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

  localStorage.setItem("keyword", keyword);
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

// small device
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

// Sự kiện tự động focus btnSearch khi nhập kí tự
searchInput.addEventListener("keydown", (event) => {
  if (event.key === "Enter") {
    event.preventDefault();
    document.querySelector("#searchButton").click();
  }
});
