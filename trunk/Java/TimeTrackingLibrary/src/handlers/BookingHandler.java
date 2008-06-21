package handlers;

import beans.TimeBookingBean;
import beans.TimeBookingTableEntryBean;
import database.AdapterPool;
import database.DBAdapter;
import exceptions.DBException;
import java.sql.Timestamp;
import java.util.Date;
import java.util.List;
import pool.ObjectPoolException;

/**
 * Handler for all booking operations
 * @author manuel
 */
public class BookingHandler
{
    /**
     * Fetches a List of booking by user ID
     * @param mid User ID 
     * @return List of bookings
     * @throws exceptions.DBException
     */
    public static List<TimeBookingTableEntryBean> getBookings(int mid) throws DBException
    {
        try
        {
            DBAdapter adapter = AdapterPool.getDBAdapter();
            List<TimeBookingTableEntryBean> returnList = null;
            
            returnList = adapter.getTimeBookings(mid);
            
            AdapterPool.releaseDBAdapter(adapter);
            return returnList;
        }
        catch (ObjectPoolException ex)
        {
            throw new DBException(ex);
        }
    }
    
    /**
     * Appends a COME booking
     * @param mid User ID 
     * @return Whether the operation was successful
     * @throws exceptions.DBException
     */
    public static boolean makeComeBooking(int mid) throws DBException
    {
        try
        {
            DBAdapter adapter = AdapterPool.getDBAdapter();
            boolean returnValue = false;
            TimeBookingBean bean = new TimeBookingBean();
            
            bean.setMid(mid);
            bean.setDate(new Date());    // set time to aktual system time
            bean.setTypId(1);            // come booking
            
            returnValue = adapter.addTimeBooking(bean);
                        
            AdapterPool.releaseDBAdapter(adapter);
            
            return returnValue;
        }
        catch (ObjectPoolException ex)
        {
            throw new DBException(ex);
        }        
    }
    
    /**
     * Appends a GO booking
     * @param mid User ID
     * @return Whether the operation was successful
     * @throws exceptions.DBException
     */
    public static boolean makeGoBooking(int mid) throws DBException
    {
        try
        {
            DBAdapter adapter = AdapterPool.getDBAdapter();
            boolean returnValue = false;
            TimeBookingBean bean = new TimeBookingBean();
            
            bean.setMid(mid);
            bean.setDate(new Date());    // set time to aktual system time
            bean.setTypId(2);            // go booking
            
            returnValue = adapter.addTimeBooking(bean);
                        
            AdapterPool.releaseDBAdapter(adapter);
            
            return returnValue;
        }
        catch (ObjectPoolException ex)
        {
            throw new DBException(ex);
        }        
    }    
}
