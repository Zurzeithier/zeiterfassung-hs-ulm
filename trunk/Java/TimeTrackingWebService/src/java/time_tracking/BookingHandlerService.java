/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package time_tracking;

import beans.TimeBookingTableEntryBean;
import exceptions.DBException;
import handlers.BookingHandler;
import java.util.List;
import javax.jws.WebMethod;
import javax.jws.WebParam;
import javax.jws.WebService;

/**
 *
 * @author manuel
 */
@WebService()
public class BookingHandlerService {

    /**
     * Web service operation
     * @param mid
     * @param number
     * @return
     * @throws DBException 
     */
    @WebMethod(operationName = "getBookings")
    public List<TimeBookingTableEntryBean> getBookings(@WebParam(name = "mid") int mid, @WebParam(name = "number") int number) throws DBException
    {
        return BookingHandler.getBookings(mid, number);
    }

    /**
     * Web service operation
     * @param mid 
     * @return
     * @throws DBException 
     */
    @WebMethod(operationName = "nextBookingIsCome")
    public boolean nextBookingIsCome(@WebParam(name = "mid") int mid) throws DBException
    {
        return BookingHandler.nextBookingIsCome(mid);
    }

    /**
     * Web service operation
     * @param mid
     * @return
     * @throws DBException 
     */
    @WebMethod(operationName = "makeComeBooking")
    public boolean makeComeBooking(@WebParam(name = "mid") int mid) throws DBException 
    {
        return BookingHandler.makeComeBooking(mid);
    }

    /**
     * Web service operation
     * @param mid 
     * @return
     * @throws DBException 
     */
    @WebMethod(operationName = "nextBookingIsGo")
    public boolean nextBookingIsGo(@WebParam(name = "mid") int mid) throws DBException
    {
        return BookingHandler.nextBookingIsGo(mid);
    }

    /**
     * Web service operation
     * @param mid
     * @return
     * @throws DBException 
     */
    @WebMethod(operationName = "makeGoBooking")
    public boolean makeGoBooking(@WebParam(name = "mid") int mid) throws DBException
    {
        return BookingHandler.makeGoBooking(mid);
    }

}
