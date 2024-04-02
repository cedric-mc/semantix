package com.but.semonkey;

import android.content.Context;
import android.content.SharedPreferences;
import android.util.Log;

import androidx.annotation.NonNull;
import androidx.work.Worker;
import androidx.work.WorkerParameters;

public class MyWorker extends Worker {

    public MyWorker(
            @NonNull Context context,
            @NonNull WorkerParameters params) {
        super(context, params);
        Log.d("VoirTest", "Worker created");
    }

    @NonNull
    @Override
    public Result doWork() {
        Log.d("VoirTest", "Le travail commence.");
        // Mettre à jour votre variable booléenne ici
        // Par exemple, vous pouvez stocker la valeur dans SharedPreferences
        SharedPreferences.Editor editor = getApplicationContext().getSharedPreferences("MyPrefs", Context.MODE_PRIVATE).edit();
        editor.putBoolean("variable_bool", true);
        editor.apply();
        Log.d("VoirTest", "Le travail est terminé.");
        return Result.success();
    }
}
