package com.example.bookstore;

import android.content.Context;
import android.graphics.Paint;
import android.support.annotation.LayoutRes;
import android.support.annotation.NonNull;
import android.support.v4.content.ContextCompat;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.CheckBox;
import android.widget.TextView;

import java.util.ArrayList;
import java.util.List;


public class BookAdapter extends ArrayAdapter<Book> {
    private Context bCont;
    private List<Book> Books = new ArrayList<>();

    public BookAdapter(@NonNull Context context, ArrayList<Book> list) {
        super(context, 0, list);
        bCont = context;
        Books = list;
    }

    @NonNull
    @Override
    public View getView(int position, @NonNull View convertView, @NonNull ViewGroup parent) {
        View listItem = convertView;
        if(GVars.INSTANCE.bookGrabType.equals("listing")) {
            if (listItem == null)
                listItem = LayoutInflater.from(bCont).inflate(R.layout.listview_layout, parent, false);

            Book currBook = Books.get(position);
            CheckBox box = listItem.findViewById(R.id.layout_checkbox);
            box.setTag(currBook.getISBN());

            TextView title = listItem.findViewById(R.id.layout_textview1);
            title.setText(currBook.getTitle());
            title.setTag(currBook.getISBN());
            title.setTextColor(ContextCompat.getColor(bCont, R.color.colorBlue));
            title.setPaintFlags(title.getPaintFlags() | Paint.UNDERLINE_TEXT_FLAG);


            EditText quant = listItem.findViewById(R.id.layout_quantity);
            quant.setTag(currBook.getISBN());
            quant.setHint("Quantity...");

            TextView cost = listItem.findViewById(R.id.layout_textview2);
            cost.setText("$ " + currBook.getCost());
            cost.setTag(currBook.getISBN());

            return listItem;
        } else {
            if (listItem == null)
                listItem = LayoutInflater.from(bCont).inflate(R.layout.listview2_layout, parent, false);

            Book currBook = Books.get(position);
            String quantCost  = currBook.getCost();
            String[] split = quantCost.split(" ");
            String quantity = split[0];
            String cost = split[1];

            TextView title = listItem.findViewById(R.id.layout_textview3);
            title.setText(currBook.getTitle());
            title.setTag(currBook.getISBN());
            title.setTextColor(ContextCompat.getColor(bCont, R.color.colorBlue));
            title.setPaintFlags(title.getPaintFlags() | Paint.UNDERLINE_TEXT_FLAG);

            TextView quant = listItem.findViewById(R.id.layout_textview4);
            quant.setText("Quantity: " + quantity);
            quant.setTag(currBook.getISBN());

            TextView bCost = listItem.findViewById(R.id.layout_textview5);
            bCost.setText("$ " + cost);
            bCost.setTag(currBook.getISBN());

            return listItem;
        }
    }
}
