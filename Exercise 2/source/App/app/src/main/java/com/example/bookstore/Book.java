package com.example.bookstore;

public class Book {
    private String isbn;
    private String title;
    private String cost;

    public Book() {
        super();
    }

    public Book(String isbn, String title, String cost) {
        this.isbn = isbn;
        this.title = title;
        this.cost = cost;
    }

    public String getISBN() {
        return isbn;
    }

    public String getTitle() {
        return title;
    }

    public String getCost() {
        return cost;
    }

    @Override
    public String toString() {
        return this.title;
    }

}
