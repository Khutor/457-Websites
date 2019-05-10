package com.example.bookstore;

import android.content.Intent;
import android.os.AsyncTask;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;

import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.Toast;
import android.widget.TextView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;

public class ListingActivity extends AppCompatActivity {
    ListView listView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_listing);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        listView = findViewById(R.id.listView);
        GVars.INSTANCE.bookGrabType = "listing";
        getJSON("http://undcemcs02.und.edu/~tyler.w.clark/457/2/listing_app.php");
    }

    private void getJSON(final String link) {

        class GetJSON extends AsyncTask<Void, Void, String> {

            @Override
            protected void onPreExecute() {
                super.onPreExecute();
            }

            @Override
            protected void onPostExecute(String s) {
                super.onPostExecute(s);
                //Toast.makeText(getApplicationContext(), s, Toast.LENGTH_SHORT).show();
                try {
                    loadIntoListView(s);
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }

            @Override
            protected String doInBackground(Void... voids) {
                try {
                    URL url = new URL(link);
                    HttpURLConnection con = (HttpURLConnection) url.openConnection();
                    StringBuilder sb = new StringBuilder();
                    BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(con.getInputStream()));
                    String json;
                    while ((json = bufferedReader.readLine()) != null) {
                        sb.append(json + "\n");
                    }
                    return sb.toString().trim();
                } catch (Exception e) {
                    return null;
                }
            }
        }
        GetJSON getJSON = new GetJSON();
        getJSON.execute();

    }

    private void loadIntoListView(String json) throws JSONException {
        JSONArray jsonArray = new JSONArray(json);
        ArrayList<Book> books = new ArrayList<>();
        for (int i = 0; i < jsonArray.length(); i++) {
            JSONObject obj = jsonArray.getJSONObject(i);
            books.add(new Book(obj.getString("bookISBN"), obj.getString("bookTitle"), obj.getString("bookCost")));
        }
        BookAdapter bAdapt = new BookAdapter(this, books);
        listView.setAdapter(bAdapt);
    }


    protected void getBook(View view) {
        TextView clickedTV = (TextView) view;
        GVars.INSTANCE.bISBN = clickedTV.getTag().toString();
        GVars.INSTANCE.bTitle = clickedTV.getText().toString();
        boolean success = getCostBook();
        if(success)
            startActivity(new Intent(this, BookActivity.class));
        else {
            //Stay
        }
    }

    protected boolean getCostBook() {
        View v;
        TextView tv;
        ListView list = findViewById(R.id.listView);
        boolean found = false;
        for(int i = 0; i < list.getCount(); i++) {
            v = list.getChildAt(i);
            tv = v.findViewById(R.id.layout_textview2);
            if(tv.getTag().toString().equals(GVars.INSTANCE.bISBN) && tv.getText().toString().contains("$")) {
                String cost = tv.getText().toString();
                String[] parts = cost.split(" ");
                GVars.INSTANCE.bCost = parts[1];
                found = true;
                break;
            }
        }

        if(found)
            return true;
        else
            return false;
    }

}