/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package database;

import pool.ObjectPool;
import application.ConfigDataProvider;
import pool.ObjectPoolException;
import java.sql.SQLException;

/**
 *
 * @author manuel
 */
public class DBAdapterObjectPool extends ObjectPool<DBAdapter>
{

    DBAdapterObjectPool(int size)
    {
        super(size);
    }

    @Override
    protected DBAdapter createObject() throws ObjectPoolException
    {
        try
        {
            return new MSServerAdapter(ConfigDataProvider.getDBData().adress, ConfigDataProvider.getDBData().user, ConfigDataProvider.getDBData().password);
        }
        catch (SQLException ex)
        {
            throw new ObjectPoolException(ex);
        }
    }

}