package handlers;

import beans.TimeBookingBean;
import beans.TimeBookingTableEntryBean;
import database.AdapterPool;
import database.DBAdapter;
import exceptions.DBException;
import java.sql.Timestamp;
import java.util.Date;
import java.util.List;
import exceptions.ObjectPoolException;

/**
 *
 * @author manuel
 */
public class BookingHandler
{
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
