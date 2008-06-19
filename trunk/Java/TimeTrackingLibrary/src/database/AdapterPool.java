package database;

import application.ConfigDataProvider;
import pool.ObjectPoolException;

/**
 *
 * @author manuel, steffen
 */
public class AdapterPool
{
    private static DBAdapterObjectPool pool = new DBAdapterObjectPool(ConfigDataProvider.getDBPoolSize());

    public synchronized static DBAdapter getDBAdapter() throws ObjectPoolException
    {
        return pool.borrowObject();
    }

    public synchronized static void releaseDBAdapter(DBAdapter adapter)
    {
        pool.returnObject(adapter);
    }

}

