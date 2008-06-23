package database;

import application.ConfigDataProvider;
import pool.ObjectPoolException;

/**
 * Pool which handles adapter objects
 * @author manuel, steffen
 */
public class AdapterPool
{
    /**
     * Pool object for DBAdapters
     */
    private static DBAdapterObjectPool pool = new DBAdapterObjectPool(ConfigDataProvider.getDBPoolSize());

    /**
     * Borrows DBAdapter from the pool
     * @return Unused DBAdapter
     * @throws pool.ObjectPoolException
     */
    public synchronized static DBAdapter getDBAdapter() throws ObjectPoolException
    {
        return pool.borrowObject();
    }

    /**
     * Returns DBAdapter back to the pool
     * @param adapter
     */
    public synchronized static void releaseDBAdapter(DBAdapter adapter)
    {
        pool.returnObject(adapter);
    }

}

